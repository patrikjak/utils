# Utils Package

The **Utils** package is a versatile utility toolkit designed to enhance Laravel applications. It provides a variety of reusable classes and functions to streamline development, including CSS and JavaScript assets to support frontend components like buttons, modals, and tables. Easily integrate the package into your project, publish assets, and selectively include only the styles and functions you need for a highly customizable setup. Perfect for Laravel developers seeking to simplify their workflow with ready-to-use utilities.

## Table of Contents

- [Installation](#installation)
- [Setup](#setup)
- [Usage](#usage)
    - [CSS](#css)
    - [JavaScript](#javascript)
        - [Dependencies](#dependencies)
---

What's included:
- **CSS** - A collection of reusable styles for buttons, modals, tables, and more.
- **JavaScript** - A variety of functions to enhance user experience and streamline development.
- **Forms** - A set of form components to simplify form creation.
- **Tables** - A collection of table components to enhance data presentation.
- **Modals** - A set of modal components to display content in a modal window.
- **Notifications** - A collection of notification components to display messages to users.

### You can find the full documentation [here](https://utils.patrikjakab.sk).

## Installation

Install the package via Composer:

```bash
composer require patrikjak/utils
```

## Setup
After installing the package, add the package provider to the providers array in bootstrap/providers.php.

```php
use Patrikjak\Utils\UtilsServiceProvider;
 
return [
    ...
    UtilsServiceProvider::class,
];
```

Next, publish the package assets:

```bash
php artisan vendor:publish --tag="assets" --force
```

## Usage
The Utils package offers a variety of useful classes and functions.

### CSS
To include all component styles, add the main.css file:
    
```html
<link rel="stylesheet" href="{{ asset('vendor/pjutils/assets/main.css') }}">
```

### JavaScript
This package does not include a compiled JavaScript file. Instead, import and call functions directly within your main JavaScript file.

Refer to the documentation for a list of available functions and usage examples.

#### Dependencies
Utils requires the following dependencies to function properly:

- [i18n-js](https://www.npmjs.com/package/i18n-js)
- [axios](https://www.npmjs.com/package/axios)

```bash
npm install i18n-js axios
```

To load correct language for JS you need to set lang attribute to html tag
    
```bladehtml
<html lang="{{ config('app.locale') }}">
```

Currently supported languages are:
- en
- sk

You will need to set up Vite build target to **esnext** in your `vite.config.js` file:

```javascript
export default defineConfig({
    ...
    build: {
        target: 'esnext',
    },
});
