from flask import Blueprint, render_template, url_for
from app.common import get_os, render_script

proxmox_routes = Blueprint('proxmox', __name__)

@proxmox_routes.route('/proxmox-6')
def proxmox_6():
    return render_script('proxmox-6.sh')

@proxmox_routes.route('/proxmox-5')
def proxmox_5():
    return render_script('proxmox-5.sh')

@proxmox_routes.route('/proxmox')
def proxmox():
    return get_os(deb_10X=url_for('proxmox.proxmox_6'), deb_9X=url_for('proxmox.proxmox_6'))
