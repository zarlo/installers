from flask import Blueprint, url_for

from app.common import redirect

runtime_routes = Blueprint('runtime', __name__, url_prefix='/runtime')


@runtime_routes.route('nvm')
def nvm():
    return redirect(url_for('runtime.nvm_', a=0, b=35, c=2))


@runtime_routes.route('nvm-<a>.<b>.<c>')
def nvm_(a, b, c):
    return redirect('https://raw.githubusercontent.com/nvm-sh/nvm/v{a}.{b}.{c}/install.sh'.format(a=a, b=b, c=c))


