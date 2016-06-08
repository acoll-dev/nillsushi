SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;


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
-- Table `gr_category`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `gr_category` (
  `idcategory` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `url` VARCHAR(255) NOT NULL,
  `idcategoryparent` INT NULL,
  `sort` INT NULL,
  `status` TINYINT(1) NOT NULL,
  `fkidmodule` INT NULL,
  PRIMARY KEY (`idcategory`),
  INDEX `idcategoryparent` (`idcategoryparent` ASC),
  INDEX `name` (`name` ASC),
  INDEX `url` (`url` ASC),
  INDEX `fk_gr_category_gr_module1` (`fkidmodule` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `gr_product`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `gr_product` (
  `idproduct` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(150) NOT NULL,
  `url` VARCHAR(255) NOT NULL,
  `code` VARCHAR(100) NULL,
  `shortdescription` VARCHAR(85) NULL,
  `description` TEXT NULL,
  `brand` VARCHAR(45) NULL,
  `manufacturer` VARCHAR(45) NULL,
  `supplier` VARCHAR(45) NULL,
  `unit` VARCHAR(10) NULL,
  `packaging` INT NULL,
  `weight` DECIMAL(7,2) NULL,
  `stock` INT NULL,
  `discount` DECIMAL(7,2) NULL,
  `unitvalue` DECIMAL(7,2) NULL,
  `amount` DECIMAL(7,2) NULL,
  `commission` INT NULL,
  `observation` VARCHAR(255) NULL,
  `keywords` VARCHAR(255) NULL,
  `picture` TEXT NULL,
  `pictures` TEXT NULL,
  `highlight` TINYINT(1) NULL,
  `sort` INT NULL,
  `registrationdate` TIMESTAMP NOT NULL,
  `status` TINYINT(1) NOT NULL,
  `fkidcategory` INT NULL,
  `fkidmodule` INT NOT NULL,
  PRIMARY KEY (`idproduct`),
  INDEX `name` (`name` ASC),
  INDEX `code` (`code` ASC),
  INDEX `fk_gr_produtct_gr_category` (`fkidcategory` ASC),
  INDEX `url` (`url` ASC),
  INDEX `fk_gr_product_gr_module1` (`fkidmodule` ASC),
  FULLTEXT INDEX `title` (`name` ASC, `shortdescription` ASC, `description` ASC, `keywords` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `gr_optional`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `gr_optional` (
  `idoptional` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `value` DECIMAL(7,2) NOT NULL,
  `info` TEXT NULL,
  `image` TEXT NULL,
  `divisible` TINYINT(1) NOT NULL,
  `list` TEXT NULL,
  `status` TINYINT(1) NOT NULL,
  `fkidmodule` INT NOT NULL,
  PRIMARY KEY (`idoptional`),
  INDEX `fk_gr_optional_gr_module1_idx` (`fkidmodule` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `gr_optionalproduct`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `gr_optionalproduct` (
  `idoptionproduct` INT NOT NULL AUTO_INCREMENT,
  `fkidoptional` INT NOT NULL,
  `fkidproduct` INT NOT NULL,
  INDEX `fk_gr_optional_has_gr_product_gr_product1_idx` (`fkidproduct` ASC),
  INDEX `fk_gr_optional_has_gr_product_gr_optional1_idx` (`fkidoptional` ASC),
  PRIMARY KEY (`idoptionproduct`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `gr_group`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `gr_group` (
  `idgroup` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `default` TINYINT(1) NOT NULL,
  PRIMARY KEY (`idgroup`))
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
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `gr_fractiongroup`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `gr_fractiongroup` (
  `idfractiongroup` INT NOT NULL AUTO_INCREMENT,
  `fkidfraction` INT NOT NULL,
  `fkidgroup` INT NOT NULL,
  `image` TEXT NULL,
  INDEX `fk_gr_fraction_has_group_group1_idx` (`fkidgroup` ASC),
  INDEX `fk_gr_fraction_has_group_gr_fraction1_idx` (`fkidfraction` ASC),
  PRIMARY KEY (`idfractiongroup`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `gr_fractionproduct`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `gr_fractionproduct` (
  `idproductfraction` INT NOT NULL AUTO_INCREMENT,
  `fkidproduct` INT NOT NULL,
  `fkidfraction` INT NOT NULL,
  INDEX `fk_gr_product_has_gr_fraction_gr_fraction1_idx` (`fkidfraction` ASC),
  INDEX `fk_gr_product_has_gr_fraction_gr_product1_idx` (`fkidproduct` ASC),
  PRIMARY KEY (`idproductfraction`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;