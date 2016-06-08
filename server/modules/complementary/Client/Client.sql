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
  `state` VARCHAR(45) NOT NULL,
  `phone1` VARCHAR(30) NULL,
  `phone2` VARCHAR(30) NULL,
  `phone3` VARCHAR(30) NULL,
  `status` VARCHAR(45) NOT NULL,
  `fkidmodule` INT NOT NULL,
  PRIMARY KEY (`idshop`),
  INDEX `fk_gr_shop_gr_module1_idx` (`fkidmodule` ASC),
  CONSTRAINT `fk_gr_shop_gr_module1`
    FOREIGN KEY (`fkidmodule`)
    REFERENCES `gr_module` (`idmodule`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `gr_client`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `gr_client` (
  `idclient` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NULL,
  `address` VARCHAR(200) NOT NULL,
  `number` VARCHAR(10) NOT NULL,
  `complement` VARCHAR(255) NULL,
  `district` VARCHAR(45) NOT NULL,
  `city` VARCHAR(45) NULL,
  `state` VARCHAR(45) NULL,
  `phone` VARCHAR(30) NULL,
  `mobilephone` VARCHAR(30) NULL DEFAULT 0,
  `preferredshop` VARCHAR(45) NULL,
  `created` DATETIME NOT NULL,
  `updated` DATETIME NULL,
  `status` TINYINT(1) NOT NULL,
  `fkidmodule` INT NOT NULL,
  `fkidshop` INT NULL,
  PRIMARY KEY (`idclient`),
  INDEX `fk_gr_client_gr_module` (`fkidmodule` ASC),
  FULLTEXT INDEX `name` (`name` ASC, `address` ASC),
  INDEX `fk_gr_client_gr_shop1_idx` (`fkidshop` ASC),
  CONSTRAINT `fk_gr_client_gr_module`
    FOREIGN KEY (`fkidmodule`)
    REFERENCES `gr_module` (`idmodule`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_gr_client_gr_shop1`
    FOREIGN KEY (`fkidshop`)
    REFERENCES `gr_shop` (`idshop`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `gr_category`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `gr_category` (
  `idcategory` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `url` VARCHAR(255) NOT NULL,
  `idcategoryparent` INT NULL DEFAULT NULL,
  `status` TINYINT(1) NOT NULL,
  `fkidmodule` INT NULL DEFAULT NULL,
  PRIMARY KEY (`idcategory`),
  INDEX `idcategoryparent` (`idcategoryparent` ASC),
  INDEX `name` (`name` ASC),
  INDEX `url` (`url` ASC),
  INDEX `fk_gr_category_gr_module1` (`fkidmodule` ASC),
  CONSTRAINT `fk_gr_category_gr_module1`
    FOREIGN KEY (`fkidmodule`)
    REFERENCES `gr_module` (`idmodule`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `gr_product`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `gr_product` (
  `idproduct` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(150) NOT NULL,
  `url` VARCHAR(255) NOT NULL,
  `code` VARCHAR(100) NULL DEFAULT NULL,
  `shortdescription` VARCHAR(85) NULL DEFAULT NULL,
  `description` TEXT NULL DEFAULT NULL,
  `brand` VARCHAR(45) NULL DEFAULT NULL,
  `manufacturer` VARCHAR(45) NULL DEFAULT NULL,
  `supplier` VARCHAR(45) NULL DEFAULT NULL,
  `unit` VARCHAR(10) NULL DEFAULT NULL,
  `packaging` INT NULL DEFAULT NULL,
  `weight` DECIMAL(7,2) NULL DEFAULT NULL,
  `stock` INT NULL DEFAULT NULL,
  `discount` DECIMAL(7,2) NULL DEFAULT NULL,
  `unitvalue` DECIMAL(7,2) NULL DEFAULT NULL,
  `amount` DECIMAL(7,2) NULL DEFAULT NULL,
  `commission` INT NULL DEFAULT NULL,
  `observation` VARCHAR(255) NULL DEFAULT NULL,
  `keywords` VARCHAR(255) NULL DEFAULT NULL,
  `picture` TEXT NULL DEFAULT NULL,
  `pictures` TEXT NULL DEFAULT NULL,
  `highlight` TINYINT(1) NULL DEFAULT NULL,
  `registrationdate` TIMESTAMP NOT NULL,
  `status` TINYINT(1) NOT NULL,
  `fkidcategory` INT NULL DEFAULT NULL,
  `fkidmodule` INT NOT NULL,
  PRIMARY KEY (`idproduct`),
  INDEX `name` (`name` ASC),
  INDEX `code` (`code` ASC),
  INDEX `fk_gr_produtct_gr_category` (`fkidcategory` ASC),
  INDEX `url` (`url` ASC),
  INDEX `fk_gr_product_gr_module1` (`fkidmodule` ASC),
  FULLTEXT INDEX `title` (`name` ASC, `shortdescription` ASC, `description` ASC, `keywords` ASC),
  CONSTRAINT `fk_gr_produto_gr_category`
    FOREIGN KEY (`fkidcategory`)
    REFERENCES `gr_category` (`idcategory`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_gr_product_gr_module1`
    FOREIGN KEY (`fkidmodule`)
    REFERENCES `gr_module` (`idmodule`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
