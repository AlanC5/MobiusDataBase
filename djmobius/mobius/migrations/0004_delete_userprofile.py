# -*- coding: utf-8 -*-
# Generated by Django 1.9.6 on 2016-05-31 03:40
from __future__ import unicode_literals

from django.db import migrations


class Migration(migrations.Migration):

    dependencies = [
        ('mobius', '0003_userprofile'),
    ]

    operations = [
        migrations.DeleteModel(
            name='UserProfile',
        ),
    ]
