**Laravel Utils**

*A set of utilities for Laravel projects.*

<!-- MarkdownTOC autolink="true" autoanchor="true" bracket="round" depth="4" -->

- [Installation](#installation)
- [Usage](#usage)
    - [Databases](#databases)

<!-- /MarkdownTOC -->

<a name="installation"></a>
# Installation

Install through composer:

    composer require peterdkc/sprocket

<a name="usage"></a>
# Usage

<a name="databases"></a>
## Databases

To create a local database (currently only support MySQL):

    php artisan sprocket:makedb

Enter your root (or otherwise privileged) username and password.

To destroy your database:

    php artisan sprocket:makedb -t

Enter your root (or otherwise privileged) username and password.
