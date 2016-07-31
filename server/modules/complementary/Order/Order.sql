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
  `fkidgallery` INT NULL,
  PRIMARY KEY (`idmodule`))
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
  PRIMARY KEY (`idclient`),
  INDEX `fk_gr_client_gr_module` (`fkidmodule` ASC),
  FULLTEXT INDEX `name` (`name` ASC, `address` ASC),
  CONSTRAINT `fk_gr_client_gr_module`
    FOREIGN KEY (`fkidmodule`)
    REFERENCES `gr_module` (`idmodule`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
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
  `status` TINYINT NOT NULL,
  `fkidmodule` INT NOT NULL,
  PRIMARY KEY (`idshop`),
  INDEX `fk_shop_gr_module1_idx` (`fkidmodule` ASC),
  CONSTRAINT `fk_shop_gr_module1`
    FOREIGN KEY (`fkidmodule`)
    REFERENCES `gr_module` (`idmodule`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `gr_daysenabled`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `gr_daysenabled` (
  `iddaysenabled` INT NOT NULL AUTO_INCREMENT,
  `date` DATETIME NOT NULL,
  `enable` TINYINT(1) NOT NULL,
  PRIMARY KEY (`iddaysenabled`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `gr_order`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `gr_order` (
  `idorder` INT NOT NULL AUTO_INCREMENT,
  `fetch` TINYINT(1) NOT NULL,
  `formpayment` VARCHAR(45) NOT NULL,
  `change` FLOAT NULL,
  `deliveryfee` FLOAT NULL,
  `subtotal` FLOAT NULL,
  `total` FLOAT NULL,
  `complement` VARCHAR(255) NULL,
  `address` VARCHAR(200) NULL,
  `number` VARCHAR(10) NULL,
  `district` VARCHAR(45) NULL,
  `city` VARCHAR(45) NULL,
  `state` VARCHAR(45) NULL,
  `created` DATETIME NOT NULL,
  `updated` DATETIME NULL,
  `status` TINYINT(1) NOT NULL,
  `fkidclient` INT NOT NULL,
  `fkidmodule` INT NOT NULL,
  `fkidshop` INT NOT NULL,
  `fkiddaysenabled` INT NULL,
  PRIMARY KEY (`idorder`),
  INDEX `fk_order_gr_client1_idx` (`fkidclient` ASC),
  INDEX `fk_gr_order_gr_module1_idx` (`fkidmodule` ASC),
  INDEX `fk_gr_order_shop1_idx` (`fkidshop` ASC),
  INDEX `fk_gr_order_gr_daysenabled1_idx` (`fkiddaysenabled` ASC),
  CONSTRAINT `fk_order_gr_client1`
    FOREIGN KEY (`fkidclient`)
    REFERENCES `gr_client` (`idclient`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_gr_order_gr_module1`
    FOREIGN KEY (`fkidmodule`)
    REFERENCES `gr_module` (`idmodule`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_gr_order_shop1`
    FOREIGN KEY (`fkidshop`)
    REFERENCES `gr_shop` (`idshop`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_gr_order_gr_daysenabled1`
    FOREIGN KEY (`fkiddaysenabled`)
    REFERENCES `gr_daysenabled` (`iddaysenabled`)
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
  `idproduct` INT NOT NULL,
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
  `registrationdate` TIMESTAMP NOT NULL,
  `status` TINYINT(1) NOT NULL,
  `fkidcategory` INT NULL,
  `fkidmodule` INT NOT NULL,
  PRIMARY KEY (`idproduct`),
  INDEX `fk_gr_product_gr_module2_idx` (`fkidmodule` ASC),
  CONSTRAINT `fk_gr_product_gr_module2`
    FOREIGN KEY (`fkidmodule`)
    REFERENCES `gr_module` (`idmodule`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `gr_product`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `gr_product` (
  `idproduct` INT NOT NULL,
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
  `registrationdate` TIMESTAMP NOT NULL,
  `status` TINYINT(1) NOT NULL,
  `fkidcategory` INT NULL,
  `fkidmodule` INT NOT NULL,
  PRIMARY KEY (`idproduct`),
  INDEX `fk_gr_product_gr_module2_idx` (`fkidmodule` ASC),
  CONSTRAINT `fk_gr_product_gr_module2`
    FOREIGN KEY (`fkidmodule`)
    REFERENCES `gr_module` (`idmodule`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `gr_orderproduct`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `gr_orderproduct` (
  `idorderproduct` INT NOT NULL AUTO_INCREMENT,
  `fkidorder` INT NOT NULL,
  `fkidproduct` INT NOT NULL,
  `quantity` SMALLINT NOT NULL,
  INDEX `fk_gr_order_has_gr_product_gr_product1_idx` (`fkidproduct` ASC),
  INDEX `fk_gr_order_has_gr_product_gr_order1_idx` (`fkidorder` ASC),
  PRIMARY KEY (`idorderproduct`),
  CONSTRAINT `fk_gr_order_has_gr_product_gr_order1`
    FOREIGN KEY (`fkidorder`)
    REFERENCES `gr_order` (`idorder`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_gr_order_has_gr_product_gr_product1`
    FOREIGN KEY (`fkidproduct`)
    REFERENCES `gr_product` (`idproduct`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;