from flask import Blueprint, url_for
from app.common import get_os, render_script, redirect


panel_routes = Blueprint('panel', __name__, url_prefix='/panel')



@panel_routes.route('/vestacp')
def vestacp():
    return redirect('http://vestacp.com/pub/vst-install.sh')


@panel_routes.route('/cpanel')
def cpanel():
    return redirect('https://securedownloads.cpanel.net/latest')


@panel_routes.route('/cpanel-dns')
def cpanel_dns():
    return redirect('https://securedownloads.cpanel.net/latest-dnsonly')


@panel_routes.route('/webmin')
def webmin():
    return get_os(deb=url_for('panel.webmin_deb'), rpm=url_for('panel.webmin_rpm'))

@panel_routes.route('/webmin_rpm')
def webmin_rpm():
    return render_script('panel/webmin_rpm.sh')

@panel_routes.route('/webmin_deb')
def webmin_deb():
    return render_script('panel/webmin_deb.sh')


