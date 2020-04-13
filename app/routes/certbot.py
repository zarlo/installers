from flask import Blueprint, render_template, url_for
from app.common import get_os, render_script

certbot_routes = Blueprint('certbot', __name__)

@certbot_routes.route('/certbot_deb')
def certbot_deb():
    return render_script('ogp-agent_deb.sh')

@certbot_routes.route('/certbot_rpm')
def certbot_rpm():
    return render_script('ogp-agent_rpm.sh')

@certbot_routes.route('/certbot')
def proxmox():
    return get_os(deb=url_for('certbot.certbot_deb'), rpm=url_for('certbot.certbot_rpm'))
