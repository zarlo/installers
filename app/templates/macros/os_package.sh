{% macro Install(package) %}

if [ {{ DATA.PACKAGE_MANAGER.APT_GET }} ] then;

    apt-get -y install {% if kwargs['apt_get'] %}{{ kwargs['apt_get'] }} {% else %} {{ package }} {% endif %}

elif [ {{ DATA.PACKAGE_MANAGER.YUM }} ] then;

    yum -y install {% if kwargs['yum'] %}{{ kwargs['yum'] }} {% else %} {{ package }} {% endif %}

elif [ {{ DATA.PACKAGE_MANAGER.DNF }} ] then;

    dnf -y install {% if kwargs['dnf'] %}{{ kwargs['dnf'] }} {% else %} {{ package }} {% endif %}

elif [ {{ DATA.PACKAGE_MANAGER.PKG }} ] then;

    pkg    install {% if kwargs['pkg'] %}{{ kwargs['pkg'] }} {% else %} {{ package }} {% endif %}

fi

{% endmacro %}

{% macro Install_File() %}

    {% if kwargs['apt_get'] %}
    if [ {{ DATA.PACKAGE_MANAGER.APT_GET }} ] then;

        apt-get -y install {{ kwargs['apt_get'] }}

    fi
    {% endif %}
    {% if kwargs['yum'] %}
    if [ {{ DATA.PACKAGE_MANAGER.YUM }} ] then;

        yum install {{ kwargs['yum'] }}

    fi
    {% endif %}
    {% if kwargs['dnf'] %}
    if [ {{ DATA.PACKAGE_MANAGER.DNF }} ] then;

        dnf install {{ kwargs['dnf'] }}

    fi
    {% endif %}
    {% if kwargs['pkg'] %}
    if [ {{ DATA.PACKAGE_MANAGER.PKG }} ] then;

        pkg install {{ kwargs['pkg'] }}

    fi
    {% endif %}

{% endmacro %}

