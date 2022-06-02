## Laravel Model File Uploader package
Easy way to upload laravel model related file from the requset.


# Install (Via Composer)
```bash
composer require plusemon/uploader
```

# Usages
use HasUploader trait on the model

```php

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Plusemon\Uploader\traits\HasUploader;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUploader;
    ...
}
```

In any where you can call UploadFromRequest via model instance with the request field name and file type(optional) then save the model.
```php
 $user->uploadFromRequest('avater','images')->save();
```

### done..!

You have uploaded you file into 

```
public/uploads/users/images/users-1-avater.jpg
```

if you need update the model file again.
you just need to call the

In any where you can call UploadFromRequest with the request field name and file type(optional) then save the model.

```php
 $user->uploadFromRequest('avater','images')->save();
```

it will
  - delete the old file from the storage
  - upload the new file into the storage
  magically :)

if you need to view the file form blade

```html
 <img src="{{ $user->urlOf('avater')">
```

it is easy or not?

even you can 
if the file not exist on this dir or missing somehow then the noimage will show there.

```blade
 <img src="{{ $user->urlOf('avater') ?? asset('images/no-image.png') }}">
```

You can delete a model with particular file like this
```php
 $user->deleteWith('avater');
````
it will delete the model with the model file.

Awesome right?

If you like my works please star my repo.
Thanks.
