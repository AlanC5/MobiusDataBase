from __future__ import unicode_literals

from django.core.validators import URLValidator
from django.db import models


# Create your models here.
class UserProfile(models.Model):
    """
    A UserProfile Model that contains the following attributes:
        - fullName
        - email
        - imageUrl
    """
    fullName = models.CharField(max_length=255)
    email = models.CharField(max_length=255, unique=True)
    imageUrl = models.TextField(validators=[URLValidator()])

    def __unicode__(self):
        return self.email
