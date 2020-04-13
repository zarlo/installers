from flask import redirect as _redirect
from flask import render_template_string, url_for, make_response, render_template

def basicKeymap(data):
    output = {}
    for key, value in data:
        for var in enumerate(key):
            output.update({var: value})

    return output

def basicBImap(data):
    output = {}
    for key, value in data.items():
        output.update({value: key})
        output.update({key: value})

    return output

def redirect(url):
    return _redirect(render_template_string(url))

def render_script(template, **content):
    resp = make_response('#!/bin/bash \n{0}'.format(render_template(template, **content).strip() ))
    resp.headers['Content-type'] = 'text/plan; charset=utf-8'
    return resp

def get_os(**kwargs):
    url_data = ''
    for key, value in kwargs.items():
        url_data = '{0}&{1}={2}'.format(url_data, key, value) 
    return _redirect(url_for('root.getos') + '?' + url_data)


