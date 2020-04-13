from app.common import basicBImap, basicKeymap


DATA = {
    "OS_IF": {
        "DEBAIN": {
            "ANY": ""
        },
        "UBUNTU": {
            "ANY": ""
        }
    },

    "PACKAGE_MANAGER": {
        "APT_GET": '-n "$(command -v apt-get)"',
        "YUM"    : '-n "$(command -v yum)"',
        "DNF"    : '-n "$(command -v dnf)"',
        "PKG"    : '-n "$(command -v pkg)"'
    },

    "DEBAIN_CODE_NAME": basicBImap({
        "12": "Bookworm",
        "11": "Bullseye",
        "10": "Buster",
        "9" : "Stretch",
        "8" : "Jessie"
    }),


    "UBUNTU_CODE_NAME": basicBImap({
        "20_04": "Focal Fossa",
        "19_04": "Disco Dingo",
        "18_04": "Bionic Beaver",
        "16_04": "Xenial Xerus",
        "14_04": "Trusty Tahr"
    }),


    "OS_CODE_NAME": {
        "debain": lambda: DATA.DEBAIN_CODE_NAME,
        "ubuntu": lambda: DATA.UBUNTU_CODE_NAME
    }
}
