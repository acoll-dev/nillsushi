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
  INDEX `category` (`name` ASC),
  INDEX `url` (`url` ASC),
  INDEX `fk_gr_category_gr_module1_idx` (`fkidmodule` ASC),
  CONSTRAINT `fk_gr_category_gr_module1`
    FOREIGN KEY (`fkidmodule`)
    REFERENCES `gr_module` (`idmodule`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `gr_banner`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `gr_banner` (
  `idbanner` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(150) NOT NULL,
  `link` VARCHAR(255) NULL,
  `width` INT NULL,
  `height` INT NULL,
  `description` TEXT NULL,
  `keywords` VARCHAR(255) NULL,
  `picture` TEXT NOT NULL,
  `sort` INT NULL,
  `registrationdate` TIMESTAMP NOT NULL,
  `status` TINYINT(1) NOT NULL,
  `fkidcategory` INT NULL,
  `fkidmodule` INT NOT NULL,
  PRIMARY KEY (`idbanner`),
  INDEX `name` (`title` ASC),
  INDEX `fk_gr_banner_gr_category_idx` (`fkidcategory` ASC),
  INDEX `fk_gr_banner_gr_module1_idx` (`fkidmodule` ASC),
  CONSTRAINT `fk_gr_banner_gr_category`
    FOREIGN KEY (`fkidcategory`)
    REFERENCES `gr_category` (`idcategory`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_gr_banner_gr_module1`
    FOREIGN KEY (`fkidmodule`)
    REFERENCES `gr_module` (`idmodule`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
