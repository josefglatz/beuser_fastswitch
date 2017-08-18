Fast Backend User Switch (TYPO3 Extension `beuser_fastswitch`)
==============================================================

> The beuser_fastswitch extension provides a very easy backend user switch for TYPO3 CMS administrator users.

## With just two mouse clicks you can

* Switch to another backend user
* Edit another backend user
* Show record info of another backend user

![Demo GIF](https://raw.githubusercontent.com/josefglatz/beuser_fastswitch/master/Documentation/Images/beuser-fastswitch-v1-0-2.gif "Extension Demo v1.0.2")

## Installation

### Installation using Composer

The recommended way to install the extension is by using [Composer](https://getcomposer.org/). In your Composer based TYPO3 project root, just do `composer require josefglatz/beuser-fastswitch`

### Installation as extension from TYPO3 Extension Repository (TER)

Download and install the extension with the TYPO3 CMS extension manager module or directly via [typo3.org](https://typo3.org/extensions/repository/view/beuser_fastswitch).

## Configuration

### Disable toolbar item for specific backend admin user/-group via UserTSConfig

```
option.backendToolbarItem.beUserFastwitch.disabled = 1
```

---

## The following requested features are already planned/already in the implementation phase

> The ongoing development is done within the develop branch!

### Phase 1) Limitation of available users via global extension configuration

* Show also backend users with administrator privileges (I don't see a usecase for that actually)
* Allowed username pattern
* Allowed user email pattern
* Allowed user group uid's
* Allowed user group name pattern

### Phase 2) Global extension configuration can be overwritten by UserTSConfig

Any of the global extension configuration can be overruled by specific UserTSConfig.

### Phase 3) Ajax based autocomplete user search box (for big TYPO3 instances)

> Requested by the community

In TYPO3 instances with for example 700+ backend users it's not possible to use the extension anymore.
An intuitive search box allows you to find the user by username or real name. After opening the module,
the last used users you've switched to (maybe a TYPO3 v9 only feature) are listed below the search box.

---

## Created by

http://josefglatz.at/
