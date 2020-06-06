<?php

namespace App;

use Spatie\MediaLibrary\MediaCollections\Models\Media as SpatieMedia;

class Media extends SpatieMedia
{
  public function destroyMedia()
  {
    $model = $this->model;
    $user = auth()->user();
    $bool = $model->authorizeMedia($this, 'delete', $user);
    if ($bool) {
      return $this->delete();
    } else {
      throw new \Exception('You are not permitted to delete this media', 501);
    }
  }
}
