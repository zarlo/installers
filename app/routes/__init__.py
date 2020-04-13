from flask import Blueprint, render_template, current_app, url_for
from app.common import get_os, render_script
root_routes = Blueprint('root', __name__)

def has_no_empty_params(rule):
    defaults = rule.defaults if rule.defaults is not None else ()
    arguments = rule.arguments if rule.arguments is not None else ()
    return len(defaults) >= len(arguments)

@root_routes.route('/')
def root_page():
    
    links = []
    for rule in current_app.url_map.iter_rules():
        # Filter out rules we can't navigate to in a browser
        # and rules that require parameters
        if "GET" in rule.methods and has_no_empty_params(rule):
            url = url_for(rule.endpoint, **(rule.defaults or {}))
            links.append(url)
    
    return render_template('index.html', urls=links)

@root_routes.route('/getos')
def getos():
    return render_script('getos.sh')

@root_routes.route('/wget')
def wget():
    return render_script('wget.sh')

@root_routes.route('/bundle/<string_list:installers>')
def bundle_fallback(installers):
    return render_script('bundle.sh', installers=installers)

from app.routes.proxmox import proxmox_routes
from app.routes.certbot import certbot_routes
from app.routes.panel import panel_routes
from app.routes.runtime import runtime_routes


def Register(app):
    app.register_blueprint(root_routes)
    app.register_blueprint(proxmox_routes)
    app.register_blueprint(certbot_routes)
    app.register_blueprint(panel_routes)
    app.register_blueprint(runtime_routes)

