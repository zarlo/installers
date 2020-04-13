{% import "macros/os_package.sh" as os_package %}

{% macro getInstaller(file) %}

{% if request.args.get('bake')%}
    {% include file + ".sh" ignore missing with context %}
{% else %}

if [ -x "$(command -v wget)" ]; then
    wget    {{ domain }}$1 | bash
elif [ -x "$(command -v curl)" ]; then
    curl -sL {{ domain }}$1 | bash
else
    {{ os_package.Install('wget') }}
    wget {{ domain }}$1 | bash
fi

{% endif %}
exit 0

{% endmacro %}


{% filter prefix("bash", request.args.get('powershell', False)) %}
. /etc/os-release

{% if request.args.get('deb') %}
if [ {{ DATA.PACKAGE_MANAGER.APT_GET }} ] then;

    {{ getInstaller(request.args.get('deb')) }}

fi
{% endif %}


{% if request.args.get('rpm')%}
if [ {{ DATA.PACKAGE_MANAGER.YUM }} ] then;

    {{ getInstaller(request.args.get('rpm')) }}

fi
{% endif %}

{% endfilter %}

{% filter prefix("posh", request.args.get('powershell', False)) %}



{% endfilter %}

{% if request.args.get('powershell', False) %}

function RunBash {
    eval "$(grep '^#bash' $0 | sed -e 's/^#bash //')"
}

"RunBash"
"exit 0"

((Get-Content $MyInvocation.MyCommand.Source) -match '^#posh' -replace '^#posh ') -join "`n" | Invoke-Expression

{% endif %}