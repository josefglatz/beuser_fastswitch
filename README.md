Fast Backend User Switch (TYPO3 Extension `beuser_fastswitch`)
==============================================================

> The `beuser_fastswitch` extension provides a very easy backend user
> switch for TYPO3 CMS administrator users.

## What does it do?

### With just two mouse clicks you can

* Switch to another backend user
* Edit another backend user (in a new tab)
* Show user info of a specific backend user (in a popup)

### It provides an AJAX based search

You can search users by **`realName`**, **`username`**, **`email`** or
**unique ID `uid`**.

The results are ordered by `lastlogin DESC`.

### The default user list

shows you all non-admin users sorted by `lastlogin DESC`.

## Screenshots and Demos

![Demo GIF](https://raw.githubusercontent.com/josefglatz/beuser_fastswitch/master/Documentation/Images/beuser-fastswitch-v1-0-2.gif "Extension Demo v1.0.2 and TYPO3 CMS 8.7LTS")

## Installation

### Installation using Composer

The recommended way to install the extension is by using
[Composer](https://getcomposer.org/). In your Composer based TYPO3
project root, just do `composer require josefglatz/beuser-fastswitch`.

### Installation as extension from TYPO3 Extension Repository (TER)

Download and install the extension with the TYPO3 CMS extension manager
module or directly via
[typo3.org](https://typo3.org/extensions/repository/view/beuser_fastswitch).

## Configuration

### Disable toolbar item for specific backend admin user/-group via UserTSConfig

```
option.backendToolbarItem.beUserFastwitch.disabled = 1
```

---

## Development

> The ongoing development is done within the develop branch!

You can use `composer require-dev
josefglatz/beuser-fastswitch:dev-develop` if you want to test the
current development state.

---

## Created by

http://josefglatz.at/

## Contribution

* [Alexander Nostadt](https://github.com/AMartinNo1) (Ajax Search)

## Support

Many thanks to my employer [supseven.at](https://www.supseven.at/) for
sponsoring work time.
