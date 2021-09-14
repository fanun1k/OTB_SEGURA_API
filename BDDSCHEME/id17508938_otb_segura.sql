-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
-- -----------------------------------------------------
-- Schema otb_segura_db
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema otb_segura_db
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `otb_segura_db` DEFAULT CHARACTER SET utf8mb4 ;
USE `otb_segura_db` ;

-- -----------------------------------------------------
-- Table `otb_segura_db`.`otb`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `otb_segura_db`.`otb` (
  `Otb_ID` INT(11) NOT NULL AUTO_INCREMENT,
  `Name` VARCHAR(100) NOT NULL,
  `State` TINYINT(4) NOT NULL DEFAULT 1,
  PRIMARY KEY (`Otb_ID`))
ENGINE = InnoDB
AUTO_INCREMENT = 10
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `otb_segura_db`.`alarm`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `otb_segura_db`.`alarm` (
  `Alarm_ID` INT(11) NOT NULL AUTO_INCREMENT,
  `Name` VARCHAR(100) NOT NULL,
  `State` TINYINT(4) NOT NULL DEFAULT 1,
  `Otb_ID` INT(11) NOT NULL,
  PRIMARY KEY (`Alarm_ID`),
  INDEX `fk_alarm_otb1_idx` (`Otb_ID` ASC),
  CONSTRAINT `fk_alarm_otb1`
    FOREIGN KEY (`Otb_ID`)
    REFERENCES `otb_segura_db`.`otb` (`Otb_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `otb_segura_db`.`alert_type`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `otb_segura_db`.`alert_type` (
  `Alert_type_ID` INT(11) NOT NULL AUTO_INCREMENT,
  `Name` VARCHAR(60) NOT NULL,
  `State` TINYINT(4) NOT NULL DEFAULT 1,
  `Otb_ID` INT(11) NOT NULL,
  PRIMARY KEY (`Alert_type_ID`),
  INDEX `fk_alert_type_otb1_idx` (`Otb_ID` ASC),
  CONSTRAINT `fk_alert_type_otb1`
    FOREIGN KEY (`Otb_ID`)
    REFERENCES `otb_segura_db`.`otb` (`Otb_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 11
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `otb_segura_db`.`user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `otb_segura_db`.`user` (
  `User_ID` INT(11) NOT NULL AUTO_INCREMENT,
  `Name` VARCHAR(100) NOT NULL,
  `Email` VARCHAR(35) NOT NULL,
  `Password` VARCHAR(60) NOT NULL,
  `Cell_phone` VARCHAR(8) NOT NULL,
  `Ci` VARCHAR(15) NOT NULL,
  `State` TINYINT(4) NOT NULL DEFAULT 1,
  `Type` TINYINT(4) NOT NULL DEFAULT 0,
  `Otb_ID` INT(11) NOT NULL,
  PRIMARY KEY (`User_ID`),
  INDEX `fk_user_otb1_idx` (`Otb_ID` ASC),
  CONSTRAINT `fk_user_otb1`
    FOREIGN KEY (`Otb_ID`)
    REFERENCES `otb_segura_db`.`otb` (`Otb_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 9
DEFAULT CHARACTER SET = utf8;

-- -----------------------------------------------------
-- Table `mydb`.`tokens`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `otb_segura_db`.`tokens` (
  `Jwt` VARCHAR(700) NULL,
  `User_ID` INT(11) NOT NULL,
  CONSTRAINT `fk_tokens_user`
    FOREIGN KEY (`User_ID`)
    REFERENCES `otb_segura_db`.`user` (`User_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `otb_segura_db`.`alert`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `otb_segura_db`.`alert` (
  `Alert_ID` INT(11) NOT NULL AUTO_INCREMENT,
  `Date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  `Longitude` FLOAT NOT NULL,
  `Latitude` FLOAT NOT NULL,
  `State` TINYINT(4) NOT NULL DEFAULT 1,
  `Otb_ID` INT(11) NOT NULL,
  `Alert_type_ID` INT(11) NOT NULL,
  `User_ID` INT(11) NOT NULL,
  PRIMARY KEY (`Alert_ID`),
  INDEX `fk_activity_otb1_idx` (`Otb_ID` ASC),
  INDEX `fk_alert_alert_type1_idx` (`Alert_type_ID` ASC),
  INDEX `fk_alert_user1_idx` (`User_ID` ASC),
  CONSTRAINT `fk_activity_otb1`
    FOREIGN KEY (`Otb_ID`)
    REFERENCES `otb_segura_db`.`otb` (`Otb_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_alert_alert_type1`
    FOREIGN KEY (`Alert_type_ID`)
    REFERENCES `otb_segura_db`.`alert_type` (`Alert_type_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_alert_user1`
    FOREIGN KEY (`User_ID`)
    REFERENCES `otb_segura_db`.`user` (`User_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `otb_segura_db`.`camera`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `otb_segura_db`.`camera` (
  `Camera_ID` INT(11) NOT NULL AUTO_INCREMENT,
  `Name` VARCHAR(100) NOT NULL,
  `State` TINYINT(4) NOT NULL DEFAULT 1,
  `Otb_ID` INT(11) NOT NULL,
  PRIMARY KEY (`Camera_ID`),
  INDEX `fk_camera_otb1_idx` (`Otb_ID` ASC),
  CONSTRAINT `fk_camera_otb1`
    FOREIGN KEY (`Otb_ID`)
    REFERENCES `otb_segura_db`.`otb` (`Otb_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
