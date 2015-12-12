# archive
ALTER TABLE archive
ADD CONSTRAINT FK_archiveCreator
FOREIGN KEY (userId) REFERENCES user(userId)
ON UPDATE CASCADE
ON DELETE CASCADE;

# accessArchive
ALTER TABLE accessArchive
ADD CONSTRAINT FK_accessArchiveUser
FOREIGN KEY (userId) REFERENCES user(userId)
ON UPDATE CASCADE
ON DELETE CASCADE;

ALTER TABLE accessArchive
ADD CONSTRAINT FK_accessArchiveArchive
FOREIGN KEY (archiveId) REFERENCES archive(archiveId)
ON UPDATE CASCADE
ON DELETE CASCADE;

# article
ALTER TABLE article
ADD CONSTRAINT FK_articleUser
FOREIGN KEY (userId) REFERENCES user(userId)
ON UPDATE CASCADE
ON DELETE CASCADE;

ALTER TABLE article
ADD CONSTRAINT FK_articleArchive
FOREIGN KEY (archiveId) REFERENCES archive(archiveId)
ON UPDATE CASCADE
ON DELETE CASCADE;

# parentAnnotation
ALTER TABLE parentAnnotation
ADD CONSTRAINT FK_parentAnnotationUser
FOREIGN KEY (userId) REFERENCES user(userId)
ON UPDATE CASCADE
ON DELETE CASCADE;

ALTER TABLE parentAnnotation
ADD CONSTRAINT FK_parentAnnotationArticle
FOREIGN KEY (articleId) REFERENCES article(articleId)
ON UPDATE CASCADE
ON DELETE CASCADE;

# textAnnotation
ALTER TABLE textAnnotation
ADD CONSTRAINT FK_textParent
FOREIGN KEY (pAnnotationId) REFERENCES parentAnnotation(pAnnotationId)
ON UPDATE CASCADE
ON DELETE CASCADE;

# youtubeVideoAnnotation
ALTER TABLE youtubeVideoAnnotation
ADD CONSTRAINT FK_youtubeVideoParent
FOREIGN KEY (pAnnotationId) REFERENCES parentAnnotation(pAnnotationId)
ON UPDATE CASCADE
ON DELETE CASCADE;

# childrenAnnotation
ALTER TABLE childrenAnnotation
ADD CONSTRAINT FK_childrenAnnotationUser
FOREIGN KEY (userId) REFERENCES user(userId)
ON UPDATE CASCADE
ON DELETE CASCADE;

ALTER TABLE childrenAnnotation
ADD CONSTRAINT FK_childrenAnnotationParent
FOREIGN KEY (pAnnotationId) REFERENCES parentAnnotation(pAnnotationId)
ON UPDATE CASCADE
ON DELETE CASCADE;
