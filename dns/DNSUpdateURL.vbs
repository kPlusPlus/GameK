' DNS URL Updater
' By Nirklars

'===================================================================================
'SETTINGS
'===================================================================================
Dim WshShell, strCurDir
Set WshShell = CreateObject("WScript.Shell")


'Change this file name to whichever you prever
logFileName = "DNSUpdateLog.txt"
'Change the path to whereever you prefer
'logFileDirectory = "E:\GameK\dns\"
logFileDirectory = WshShell.CurrentDirectory ' set CurrentDirectory

dim allDomains, currDomain
allDomains = 3
currDomain = 1


'MsgBox "curr dir " & logFileDirectory
'This is where you copy and paste your own update url
'ORIG updateURL = "http://freedns.afraid.org/dynamic/update.php?herebemanyrandomletters"
' updateURL = "https://freedns.afraid.org/dynamic/update.php?VU12QDlLMzFVMVVBQUxaN0IxTUFBQUFCOjE2NjM4NTg1"

'===================================================================================
'DECLARATIONS
'===================================================================================

dim localPath, logFilePath

'Find local path where the script run from
localPath = CreateObject("Scripting.FileSystemObject").GetAbsolutePathName(".")

'Declare Log function variables
Dim logFile, oFSO
logFilePath = logFileDirectory & "\" & logFileName

'Declare File System object in order to write to log file
Set oFSO = CreateObject("Scripting.FileSystemObject")

'Declare http object
Set oXMLHTTP = CreateObject("MSXML2.XMLHTTP.3.0")


'===================================================================================
'PROGRAM
'===================================================================================
updateURL = "https://freedns.afraid.org/dynamic/update.php?VU12QDlLMzFVMVVBQUxaN0IxTUFBQUFCOjE2NjM4NTg1"
Log(httpGET(updateURL))
updateURL = "https://freedns.afraid.org/dynamic/update.php?VU12QDlLMzFVMVVBQUxaN0IxTUFBQUFCOjg3NjM0NTQ="
Log(httpGET(updateURL))
updateURL = "https://freedns.afraid.org/dynamic/update.php?VU12QDlLMzFVMVVBQUxaN0IxTUFBQUFCOjg3NjM0NTY="
Log(httpGET(updateURL))

'===================================================================================
'FUNCTIONS
'===================================================================================

'Retrieve a webpage
function httpGET(strURL)
	On Error Resume Next
		oXMLHTTP.Open "GET", strURL, False
		oXMLHTTP.Send
		If oXMLHTTP.Status = 200 Then
			httpGET = oXMLHTTP.responseText ' function return 
		else
			httpGET = "http GET failed" ' function return 
		end if
		oXMLHTTP.Close
end function

'Write to a logfile
sub Log (msg)
	if oFSO.FileExists(logFilePath) then
		Set oTextFileCheck = oFSO.GetFile(logFilePath)
		'Check if the log file has become larger than 1 mb
		if oTextFileCheck.Size > 1048576 Then
      'If its too large, delete it
			oTextFileCheck.Delete
		end if
	end if
	
	Set oTextFile = oFSO.OpenTextFile(logFilePath, 8, True)
	oTextFile.writeline date & " " & time & ": " & msg
	oTextFile.close
	
' message about proccess
	if(InStr(msg,"ERROR") >0) then
        MsgBox("*** " & msg)
    end if
    if(InStr(msg,"Updated") >0) then
        MsgBox(msg)
    end if
	
end sub

