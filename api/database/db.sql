-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema viculturadb
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema viculturadb
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `viculturadb` DEFAULT CHARACTER SET utf8 ;
USE `viculturadb` ;

-- -----------------------------------------------------
-- Table `viculturadb`.`materials`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `viculturadb`.`materials` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(1024) NOT NULL,
  `year` INT NOT NULL,
  `date_created` DATETIME NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `viculturadb`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `viculturadb`.`users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(100) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `password` VARCHAR(1024) NOT NULL,
  `date_created` DATETIME NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `viculturadb`.`sessions`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `viculturadb`.`sessions` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `token` VARCHAR(1024) NOT NULL,
  `date_created` DATETIME NOT NULL,
  `date_expiration` DATETIME NOT NULL,
  `id_user` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_sessions_users_idx` (`id_user` ASC) VISIBLE,
  CONSTRAINT `fk_sessions_users`
    FOREIGN KEY (`id_user`)
    REFERENCES `viculturadb`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `viculturadb`.`groups`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `viculturadb`.`groups` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `description` VARCHAR(150) NOT NULL,
  `date_created` DATETIME NOT NULL,
  `dateEnd` DATETIME NULL,
  `id_user` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_groups_users1_idx` (`id_user` ASC) VISIBLE,
  CONSTRAINT `fk_groups_users1`
    FOREIGN KEY (`id_user`)
    REFERENCES `viculturadb`.`users` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `viculturadb`.`objectives`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `viculturadb`.`objectives` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `min_progress` INT NOT NULL,
  `max_progress` INT NOT NULL,
  `date_created` DATETIME NOT NULL,
  `id_group` INT NOT NULL,
  `id_material` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_objectives_groups1_idx` (`id_group` ASC) VISIBLE,
  INDEX `fk_objectives_materials1_idx` (`id_material` ASC) VISIBLE,
  CONSTRAINT `fk_objectives_groups1`
    FOREIGN KEY (`id_group`)
    REFERENCES `viculturadb`.`groups` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_objectives_materials1`
    FOREIGN KEY (`id_material`)
    REFERENCES `viculturadb`.`materials` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

alter table materials add column url_image varchar(1024) null;
alter table materials add column url_details varchar(1024) null;
alter table objectives add column current_progress int not null;

INSERT INTO materials VALUES(1, 'The Sopranos', 1999, '2021-07-14 13:07:00', 'https://m.media-amazon.com/images/M/MV5BZGJjYzhjYTYtMDBjYy00OWU1LTg5OTYtNmYwOTZmZjE3ZDdhXkEyXkFqcGdeQXVyNTAyODkwOQ@@._V1_QL75_UY562_CR8,0,380,562_.jpg', 'https://www.imdb.com/title/tt0141842/');
INSERT INTO materials VALUES(2, 'Mr. Robot', 2015, '2021-07-14 14:16:00', 'https://m.media-amazon.com/images/M/MV5BMzgxMmQxZjQtNDdmMC00MjRlLTk1MDEtZDcwNTdmOTg0YzA2XkEyXkFqcGdeQXVyMzQ2MDI5NjU@._V1_QL75_UX380_CR0,4,380,562_.jpg', 'https://www.imdb.com/title/tt4158110/');
INSERT INTO materials VALUES(3, 'Breaking Bad', 2018, '2021-07-14 14:16:00', 'https://m.media-amazon.com/images/M/MV5BMjhiMzgxZTctNDc1Ni00OTIxLTlhMTYtZTA3ZWFkODRkNmE2XkEyXkFqcGdeQXVyNzkwMjQ5NzM@._V1_QL75_UY562_CR12,0,380,562_.jpg', 'https://www.imdb.com/title/tt0903747/');

INSERT INTO users VALUES(1, 'test', "test@andorra.ad", "test", "2021-07-20 02:20:00");
INSERT INTO users VALUES(2, 'admin', "ricardo@andorra.ad", "$2a$10$.vFI.ADhcM2AMLnFRFdcrOAxnlrDBeujti7VseJm0kXEI/JJAXaby", "2021-08-03 02:20:00");
INSERT INTO users VALUES(3, 'admin2', "ricardo@andorra.ad", "$2a$10$IjvY08SU.RW2DpULFCLkeuF5OkOZC24ykIeh6GlCxNDIa8lpWxBYa", "2021-08-03 02:20:00");

INSERT INTO viculturadb.groups VALUES(1, "test_group", "This is only a test", "2021-07-20 02:21:00", NULL, 1);
INSERT INTO viculturadb.groups VALUES(2, "test_group2", "This is only a test2", "2021-07-20 02:21:00", NULL, 1);
INSERT INTO viculturadb.groups VALUES(3, "another test", "This is another test doing post request in postman", "2021-07-20 02:21:00", NULL, 2);

INSERT INTO objectives VALUES(1, 0, 10, "2021-07-20 02:21:00", 1, 1, 0);
INSERT INTO objectives VALUES(2, 0, 15, "2021-07-20 02:21:00", 1, 2, 0);
INSERT INTO objectives VALUES(3, 0, 15, "2021-07-20 02:21:00", 2, 3, 0);