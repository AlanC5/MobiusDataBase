from django.shortcuts import render
from django.http import HttpResponse
from oauth2client import client, crypt


CLIENT_ID = "102629415692-92fn510gpjosketphv6i2gbs1ds6o64l.apps.googleusercontent.com"

# Create your views here.
def index(request):
    return render(request, 'mobius/index.html', {})


def profile(token):
    try:
        idinfo = client.verify_id_token(token, CLIENT_ID)
        # If multiple clients access the backend server:
        if idinfo['iss'] not in ['accounts.google.com', 'https://accounts.google.com']:
            raise crypt.AppIdentityError("Wrong issuer.")

    except crypt.AppIdentityError:
        raise Exception("Bad Token")
    
    userid = idinfo['sub']

    return HttpResponse(userid)