# 9 tables so far, still requires imageAnnotation table

# user
CREATE TABLE user (
	userId smallint(5) unsigned NOT NULL AUTO_INCREMENT,
	email varchar(100) NOT NULL,
	name varchar(100) NOT NULL,
	password varchar(100) NOT NULL,
  imagefile text NOT NULL,
	PRIMARY KEY (userid)
) ENGINE=InnoDB;

# archive
CREATE TABLE archive (
	archiveId int(10) unsigned NOT NULL AUTO_INCREMENT,
	userId smallint(5) unsigned NOT NULL,
	archiveName varchar(100) NOT NULL,
	icon varchar(20) NOT NULL,
	description text,
  private tinyint(1) NOT NULL,
	saveDate datetime NOT NULL,
	PRIMARY KEY (archiveid)
) ENGINE=InnoDB;

# accessArchive
CREATE TABLE accessArchive (
  accessId int(10) unsigned NOT NULL AUTO_INCREMENT,
  userId smallint(5) unsigned NOT NULL,
  archiveId int(10) unsigned NOT NULL,
  PRIMARY KEY (accessId)
) ENGINE=InnoDB;


# article
CREATE TABLE article (
	articleId int(10) unsigned NOT NULL AUTO_INCREMENT,
	archiveId int(10) unsigned NOT NULL,
	userId smallint(5) unsigned NOT NULL,
	articleName text NOT NULL,
  description text,
  private tinyint(1) NOT NULL,
  saveDate datetime NOT NULL,
  articleURL text NOT NULL,
  imageURL text NOT NULL,
	PRIMARY KEY (articleid)
) ENGINE=InnoDB;

# parentAnnotation
CREATE TABLE parentAnnotation (
  pAnnotationId int(10) unsigned NOT NULL AUTO_INCREMENT,
  userId smallint(5) unsigned NOT NULL,
  articleId int(10) unsigned NOT NULL,
  comment text NOT NULL,
  likes int(10) unsigned,
  annotationType varchar(20) NOT NULL,
  numChildrenAnnotation int(10) unsigned NOT NULL,
	saveDate datetime NOT NULL,
	color varchar(20) NOT NULL,
  PRIMARY KEY (pAnnotationId)
) ENGINE=InnoDB;

# imageAnnotation


# textAnnotation
CREATE TABLE textAnnotation (
  annotationId int(10) unsigned NOT NULL AUTO_INCREMENT,
  pAnnotationId int(10) unsigned NOT NULL,
  annotatedText text NOT NULL,
  textPosition int(10) unsigned NOT NULL,
  PRIMARY KEY (annotationId)
) ENGINE=InnoDB;

# youtubeVideoAnnotation
CREATE TABLE youtubeVideoAnnotation (
  annotationId int(10) unsigned NOT NULL AUTO_INCREMENT,
  pAnnotationId int(10) unsigned NOT NULL,
  annotatedTime int(10) unsigned NOT NULL,
  PRIMARY KEY (annotationId)
) ENGINE=InnoDB;

# childrenAnnotation
CREATE TABLE childrenAnnotation (
  cAnnotationId int(10) unsigned NOT NULL AUTO_INCREMENT,
  pAnnotationId int(10) unsigned NOT NULL,
  userId smallint(5) unsigned NOT NULL,
  comment text NOT NULL,
  likes int(10) unsigned,
  PRIMARY KEY (cAnnotationId)
) ENGINE=InnoDB;

# betaEmailList
CREATE TABLE betaEmailList (
  betaId int(10) unsigned NOT NULL AUTO_INCREMENT,
  name varchar(100) NOT NULL,
  email varchar(100) NOT NULL,
  PRIMARY KEY (betaId)
) ENGINE=InnoDB;
