<?php

/**
 * @author Jiju Thomas Mathew <jijutm@saturn.in>
 * @package MsSQL Backup using PHP, could be run as standalone, see implementation at the bottom.
 * 
 */

/*
 * Thanks to: php team, php manual maintainers, my colleagues
 * References: 
 * 	1. http://databases.aspfaq.com/schema-tutorials/schema-how-do-i-show-all-the-triggers-in-a-database.html
 *  2. http://msdn.microsoft.com/en-us/library/aa258255(SQL.80).aspx
 * Tools Used:
 *  Zend Studio (http://www.zend.com)
 *  Aqua Data Studio (http://www.aquafold.com) 
 * Caveats:
 *  Relations and Foreign keys are not checked, and will be ignored if any. 
 *  Default values of NON NULL Fields are not honored. 
 *  Will appreciate suggestions from an MSSQL expert in these aspects.
 */

class phpMSSqlBackup {
	
	private $conn;
	private $config;
	private $store;
	private $ts;
	private $objNames = array('P' => 'Proceedure', 'FN' => 'Function', 'TR' => 'Trigger');
	
	public $warnings;

	function __construct($config) {
		$this->connect($config);
		$tmp = tempnam(null,null);
		unlink($tmp);
		$this->warnings = array();
		$this->store = dirname($tmp) . '/' . $config['db'] . '_' . date("Ymd-G") . '.sql'; 
		file_put_contents($this->store,''); // Empty the store
	}
	
	/**
	 * creates the connection
	 * @param {struct} $config
	 */

	function connect($config) {
		$config_default = array('host' => 'sql5031.myasp.net', 'user' => 'DB_A117A2_kPlusPlus_admin', 'pass' => 'kreso1004', 'db' => false, 'compress' => false);
		$config = $config + $config_default;
		if($config['db'] == false){
			die("Please configure the Database");
		}
		$this->conn = mssql_connect($config['host'], $config['user'], $config['pass']) or die("Could not connect ".mssql_get_last_message());
		mssql_select_db($config['db']);
		$this->config = $config;
	}
	
	/**
	 * 
	 */
	function __destruct() {
		mssql_close($this->conn);
	}
	
	/**
	 * @desc fetch data from database using query
	 * @param string $query
	 * @param int $as
	 */
	
	function getData($query){
		$rv = array();
		$rs = mssql_query($query);
		if(mssql_num_rows($rs) > 0){
		  if(mssql_num_fields($rs) > 1){	
			while( ($rd = mssql_fetch_assoc($rs)) !== false)
				array_push($rv, $rd);
		  }else{
		  	if(mssql_num_rows($rs) == 1){
		  		$rv = mssql_fetch_row($rs);
		  		return $rv[0];
		  	}else{
			   while( ($rd = mssql_fetch_row($rs)) !== false)
				  array_push($rv, $rd[0]);
		  	}	
		  }		
		return $rv;
		}else
			return false;		
	}
	
	/**
	 * @desc runs a query, where the response, or selected data is not much important
	 * 		 like an update, or insert, or create statement
	 * @param string $query
	 */
	
	function runQuery($query){
		$rv = mssql_query($query);
		return $rv;
	}
	
	
	function runBackup(){

		$this->addToSchema($this->header());
		/** get the list of tables **/
		$tables = $this->getData(sprintf("select table_name from information_schema.tables where table_catalog = '%s'", $this->config['db']));
		
		/** for each of the tables, lets run the schema, and data */
		foreach($tables as $table){
			$this->writeTableSchema($table);
		}
		
		/* lets proceed with the routines, or procedures */
		$this->writeSysObjects('P'); // procedures
	
		/* continue with the scalar functions */
		$this->writeSysObjects('FN'); // functions

		/* finish off with the triggers, at last since restore will also be in this order */
		$this->writeSysObjects('TR'); // triggers
		
		$this->addToSchema($this->footer());
		
		/* complete the job */
		$this->deliverBackup();
	}
	
	function writeSysObjects($xType){
		$rs = mssql_query("select name from sysobjects where xtype = '$xType'");
		while(($rd = mssql_fetch_row($rs)) !== false){
			$schema = "\n\n--\n".'-- Writing SQL of ' . $this->objNames[$xType] . ' ' .$rd[0]."\n--\n";
			$this->addToSchema($schema . "\n");
			$sql = $this->getData("EXEC sp_helptext '".$rd[0]."'");
			if(!empty($sql) && is_array($sql))
			   $this->addToSchema(join($sql) . "\nGO\n");
		}
	}
	
	function writeTableSchema($table){
		$objectId = $this->getData("select object_id from sys.all_objects where name = '$table' and type_desc = 'USER_TABLE'");
		$identity_column = $this->getData("select name, seed_value, increment_value from sys.identity_columns where object_id = " . $objectId);
		$all_columns = $this->getData("select * from information_schema.columns where table_name='$table' order by ordinal_position");
		$struct = array();
		$schema = "\n--\n-- Dumping Structure for $table\n--\n\n";
		$sql = 'CREATE TABLE ['.$all_columns[0]['TABLE_SCHEMA'].'].['.$table.'](';
		foreach ($all_columns as $k => $column){
			if($k > 0) $sql .= ',';
			$identity = (isset($identity_column[0]['name']) && $column['COLUMN_NAME'] == $identity_column[0]['name'])?' IDENTITY('.$identity_column[0]['seed_value'].','.$identity_column[0]['increment_value'].')':"";
			$null = ($column['IS_NULLABLE'] == 'NO')?' NOT NULL ':'';
			$width = (!empty($column['CHARACTER_MAXIMUM_LENGTH']) && ($column['DATA_TYPE'] !== 'text'))?'(' .$column['CHARACTER_MAXIMUM_LENGTH'] . ')':'';  
			$sql .= "\n\t" . $column['COLUMN_NAME']."\t".$column['DATA_TYPE'].$width. "\t". $null . $identity;
			$struct[$column['COLUMN_NAME']] = array('type' => $column['DATA_TYPE'], 'null' => $column['IS_NULLABLE']);
		}	
		$sql .= "\n)\nGO\n";
		
		$this->addToSchema($schema . $sql . "\n");

		$rs = mssql_query("select * from $table");
		if(mssql_num_rows($rs) > 0){
			$this->addToSchema("\n--\n-- Dumping data for $table\n--\n\n");	
			$this->addToSchema("BEGIN\n");	
			if($identity_column !== false)
				$this->addToSchema('SET IDENTITY_INSERT "'.$table.'" ON' . "\n");
			while(($rd = mssql_fetch_assoc($rs)) !== false){
			    $this->addToSchema($this->buildInsertQuery($rd, $table, $struct) . "\n");
			}
			if($identity_column !== false)
				$this->addToSchema('SET IDENTITY_INSERT "'.$table.'" OFF' . "\n");
			$this->addToSchema("END\nGO\n");
		}		
	}
	
	function addToSchema($schema){
		file_put_contents($this->store, $schema, FILE_APPEND);
	}
	
	function buildInsertQuery($a, $table, $struct){
		
		// do some value escaping
		foreach($a as $field => $value){
			switch($struct[$field]['type']){
				case 'varchar':
				case 'char':
				case 'text':
				case 'datetime':
					if(!is_null($value)) $a[$field] = "'" . str_replace("'","''", $value) . "'";
					elseif($struct[$field]['null'] == 'NO') $a[$field] = "''";
					else  $a[$field] = "NULL";
				break;	
				case 'numeric':
				case 'int':
					$a[$field] = sprintf("%d", $value);
				break;
			}
		}
		
		return 'INSERT INTO ' . $table . ' ('.join(',', array_keys($a)).') VALUES ('.join(',',array_values($a)).');';
	}
	
	function header(){
		$rv = "-- Backup of " . $this->config['db'] . "\n";
		$this->ts = microtime(true);
		$rv .= "-- Started at " . date("M d, Y h:s:i") . preg_replace("@^0|\s(.*)@",'',microtime()) . ' ' . date("a");
		return $rv . "\n\n";
	}
	
	function footer(){
		$time = microtime(true) - $this->ts;
		$rv = "-- Completed at " . date("M d, Y h:s:i") . preg_replace("@^0|\s(.*)@",'',microtime()) . ' ' . date("a") . "\n";
		$rv .= "-- \t\tTaking about $time seconds \n";
		$rv .= "-- jijutm@saturn.in [http://www.saturn.in | http://www.php-trivandrum.org]\n";
		return $rv;
	}
	
	function deliverBackup(){
		$ContentType = 'text/x-sql';
		
		if($this->config['compress'] == true){
		  if(class_exists('ZipArchive')){	
			
			$zip = new ZipArchive();
			$filename = $this->store . '.zip';
			
			if ($zip->open($filename, ZIPARCHIVE::CREATE) == TRUE) {
				$zip->addFile($this->store, basename($this->store));
				$zip->close();
				$ContentType = 'application/octet-stream';
				$this->store = $filename;
				
				$tmp = pathinfo($this->config['out_file'],PATHINFO_EXTENSION);
				if($tmp !== 'zip')
					$this->config['out_file'] .= '.zip';
			}else{
				$this->warnings[] = 'Could not create zip file.. skipping compression';
			}		
		  }else{
				$this->warnings[] = 'Compression library not enabled or unsupported php version.. skipping compression';
		  }	
		}

		// if we have a file name in the config
		if(isset($this->config['out_file'])){
			@rename($this->store, $this->config['out_file']);
		}else{
			/** okay we are sending this file.. */
			header("Content-type: " . $ContentType);
			header("Content-disposition: attachment; filename=" . $this->store);
			readfile($this->store);
			unlink($this->store);
		}
	}
}

$cfg = array();
//$cfg['host'] = 'localhost';
//$cfg['user'] = 'sa'; // only on our test server
//$cfg['pass'] = '123456'; // only on our test server
$cfg['db'] = 'Xeproof'; // the rest is already there (localhost/sa/123456) 
						// if interested in what xeproof is .. check http://www.xeproof.com

// define if you need to compress using zip, depends on existance of php zip support.. otherwise
// compression will be gracefully skipped
//$cfg['compress'] = true;
// define outfile to write to the file, make use of cron/scheduler to take the backups on server itself
// if not defined, the backup will be provided as attachment, assuming the backup is invoked from the
// browser.
$cfg['out_file'] = './' . $cfg['db'] . '.sql'; 

$bo = new phpMSSqlBackup($cfg);
$bo->runBackup();

if(!empty($bo->warnings) && !empty($cfg['out_file']))
  echo "Messages:" . join("\n\t",$bo->warnings);
  