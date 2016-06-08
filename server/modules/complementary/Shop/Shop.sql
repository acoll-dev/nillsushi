SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';


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
-- Table `gr_shop`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `gr_shop` (
  `idshop` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `address` VARCHAR(200) NOT NULL,
  `number` INT NOT NULL,
  `complement` VARCHAR(45) NULL,
  `district` VARCHAR(45) NOT NULL,
  `city` VARCHAR(45) NOT NULL,
  `state` VARCHAR(45) NULL,
  `phone1` VARCHAR(30) NULL,
  `phone2` VARCHAR(30) NULL,
  `phone3` VARCHAR(30) NULL,
  `status` TINYINT NOT NULL,
  `fkidmodule` INT NOT NULL,
  PRIMARY KEY (`idshop`),
  INDEX `fk_shop_gr_module_idx` (`fkidmodule` ASC),
  CONSTRAINT `fk_shop_gr_module`
    FOREIGN KEY (`fkidmodule`)
    REFERENCES `gr_module` (`idmodule`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
