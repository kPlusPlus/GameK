-- https://www.scirra.com/tutorials/4839/creating-your-own-leaderboard-highscores-easy-and-free-php-mysql/creating-your-own-leaderboard-highscores-easy-and-free-php-mysql/page-2

CREATE TABLE `scores` (
`id` INT NOT NULL AUTO_INCREMENT ,
`name` VARCHAR( 20 ) NOT NULL ,
`score` INT NOT NULL ,
PRIMARY KEY ( `id` )
) ENGINE = InnoDB;