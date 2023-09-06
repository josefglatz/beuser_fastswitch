Fast Backend User Switch (TYPO3 Extension `beuser_fastswitch`)
==============================================================

> The `beuser_fastswitch` extension provides a very easy backend user
> switch for TYPO3 CMS administrator users.

## What does it do?

### With just two mouse clicks you can

* Switch to another backend user
* Edit another backend user (in a new tab)
* Show user info of a specific backend user (in a modal)

### It provides an AJAX based search

You can search users by **`realName`**, **`username`**, **`email`** or
**unique ID `uid`**.

### Ordering of listed users

The results are always ordered by `lastlogin DESC`. Since this speeds up
also the support if some editor is calling you that something isn't
working. You can then easily switch to the backend user without
searching for it in the TYPO3 backend user module.

### User list item features

* Trigger contextmenu of backend user record (by clicking on the avatar/icon)
* Edit backend user record (by clicking on the username or realName)
* Show element information popup (by clicking on the Info-Button)
* Switch to the backend user (by clicking on the Switch-Button)

## Screenshots and Demos

[<img src="https://i.ytimg.com/vi/VVBjrTTaPHU/maxresdefault.jpg" width="50%">](https://www.youtube.com/watch?v=VVBjrTTaPHU "Extension Demo v5 and TYPO3 CMS 12.4 LTS")

## Requirements

**Actual main-Branch:**

1. TYPO3 core version support: 12 LTS
2. PHP version: >= 8.1

**Version Matrix**

| TYPO3 version    | PHP version    | Extension version | Notes               | Install                                        |
|------------------|----------------|-------------------|---------------------|------------------------------------------------|
| 8.7.0 - 10.4.99  | >= 7.2         | 3.x               |                     | `composer req josefglatz/beuser-fastswitch:^3` |
| 11.5.0 - 11.5.99 | >= 7.2         | 4.x               | no breaking changes | `composer req josefglatz/beuser-fastswitch:^4` |
| 12.4.5 - 12.4.99 | >= 8.1, =< 8.2 | 5.x               | no breaking changes | `composer req josefglatz/beuser-fastswitch:^5` |

## Installation

### Installation using Composer

The recommended way to install the extension is by using
[Composer](https://getcomposer.org/). In your Composer based TYPO3
project root, just do `composer require josefglatz/beuser-fastswitch`.

> **TYPO3 core version === 11 LTS support by using version 4.x:** `composer req josefglatz/beuser-fastswitch:^4`
> **TYPO3 core version =< 10 LTS support by using version 3.x:** `composer req josefglatz/beuser-fastswitch:^3`

### Installation as extension from TYPO3 Extension Repository (TER)

Download and install the extension with the TYPO3 CMS extension manager
module or directly via
[typo3.org](https://typo3.org/extensions/repository/view/beuser_fastswitch).

## Configuration

### Disable toolbar item for specific backend admin user/-group via UserTSConfig

```
options.backendToolbarItem.beUserFastwitch.disabled = 1
```

---

## Development

> The ongoing development is done within the main branch!

You can use `composer require-dev
josefglatz/beuser-fastswitch:dev-main` if you want to test the
current development state.

---

## Created by

http://josefglatz.at/

## Contribution

* [Alexander Nostadt](https://github.com/AMartinNo1) (Initial ajax search feature)
* [Andreas Fernandez](https://github.com/andreasfernandez) (Rewrite jQuery to ES6 with proper Core API usage)

## Support

Many thanks to my employer [supseven.at](https://www.supseven.at/) for
sponsoring work time.
