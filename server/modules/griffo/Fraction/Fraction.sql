SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';


-- -----------------------------------------------------
-- Table `gr_group`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `gr_group` (
  `idgroup` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `default` TINYINT(1) NOT NULL,
  `status` TINYINT(1) NOT NULL,
  PRIMARY KEY (`idgroup`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `gr_module`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `gr_module` (
  `idmodule` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `struct` TINYINT(1) NOT NULL,
  `path` VARCHAR(255) NULL,
  `searchable` TINYINT(1) NOT NULL,
  `status` TINYINT(1) NOT NULL,
  PRIMARY KEY (`idmodule`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `gr_fraction`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `gr_fraction` (
  `idfraction` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `formula` VARCHAR(45) NOT NULL,
  `fkidmodule` INT NOT NULL,
  PRIMARY KEY (`idfraction`),
  INDEX `fk_gr_fraction_gr_module1_idx` (`fkidmodule` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci
COMMENT = '						';


-- -----------------------------------------------------
-- Table `gr_groupfraction`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `gr_groupfraction` (
  `idgroupfraction` INT NOT NULL AUTO_INCREMENT,
  `fkidgroup` INT NOT NULL,
  `fkidfraction` INT NOT NULL,
  `image` TEXT NULL,
  PRIMARY KEY (`idgroupfraction`),
  INDEX `fk_gr_group_has_gr_fraction_gr_fraction1_idx` (`fkidfraction` ASC),
  INDEX `fk_gr_group_has_gr_fraction_gr_group_idx` (`fkidgroup` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
