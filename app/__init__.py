from werkzeug.routing import BaseConverter
from app.routes import Register
from flask import Flask

app = Flask(__name__)


@app.template_filter('prefix')
def add_prefix(value, prefix, run=True):
    if run is False:
        return value
    return  '\n#{0} '.format(prefix).join(value.splitlines()) 

class StringListConverter(BaseConverter):
    regex = r'/([^,]+)/g'

    def to_python(self, value):
        return [x for x in value.split(',')]

    def to_url(self, value):
        return ','.join(x for x in value)

from app.global_data import DATA

app.jinja_env.globals['DATA'] = DATA

app.url_map.converters['string_list'] = StringListConverter

Register(app)

