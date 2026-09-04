from django.urls import path

from . import views

urlpatterns = [
    path("check/", views.check_requirements, name="check_requirements"),
    path("reference/", views.reference_data, name="reference_data"),
    path("health/", views.healthcheck, name="healthcheck"),
]
