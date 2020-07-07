<?php
namespace App\Traits;

/**
 *
 */
trait HasImage
{
  public function saveImage($image, $collection, $getMedia = false){
    $medias = [];
    if (\is_array($image))
      foreach ($image as $img)
        $medias[] = $this->uploadImage($img, $collection);
    else $medias[] = $this->uploadImage($image, $collection);
    if ($getMedia) {
      return $medias;
    } else {
      return $this->withUrls($collection, \is_array($image), $medias);
    }
  }

  public function withImageUrl($medias = null, $collection, $is_array = false){
    if (!$medias) $medias = $is_array ? $this->getMedia($collection) : $this->getFirstMedia($collection);

    $this->generateCollectionUrl($medias, $is_array, $collection);
    return $this;
  }

  private function generateCollectionUrl(&$medias, $is_array, $collection) {
    if ($medias) {
      $images;
      if ($is_array) {
        $images = [];
        // dd($is_array, $medias);
        $medias = $this->getMedia($collection);
        for ($i=0; $i < sizeof($medias); $i++) {
          $images[] = $this->imageObj($medias[$i]);
        }
      } else {
        $images = $this->imageObj(is_array($medias) ? $medias[0] : $medias);
      }
      if ($images) $this->$collection = $images;
    }
  }

  private function generateCollectionsUrl($collection, $medias = false){
    $is_array = $this->collectionIsArray($collection);
    $collection_name_is_array = is_array($collection);
    $collection_name = $collection_name_is_array ? $collection[0] : $collection;
    $collection_medias = !$medias ? $is_array ? $this->getMedia($collection_name) : $this->getFirstMedia($collection_name) : $medias;

    $this->generateCollectionUrl($collection_medias, $is_array, $collection_name);
  }

  private function collectionIsArray($collection){
   $collection = $this->getMediaCollection(is_array($collection) ? $collection[0] : $collection);
   return $collection ? $collection->collectionSizeLimit > 1 : false;
 }

  public function withUrls($collections, $is_array = false, $medias = null){
    if (is_array($collections)) {
      foreach ($collections as $collection) {
        $this->generateCollectionsUrl($collection, $medias);
      }
    } else {
      $this->generateCollectionsUrl($collections, $medias);
    }
    return $this;
  }

  private function imageObj($media){
    $image = new \stdClass();
    $image->thumb   = $media->getUrl('thumb');
    $image->medium  = $media->getUrl('medium');
    $image->url     = $media->getUrl();
    $image->metas   = $media->custom_properties;
    return $image;
  }

  public function uploadImage($image, $collection){
    // $image = 'data:image/png;base64,'.$image;
    $name           = $collection;
    $type           = strpos($image, ';');
    $type           = explode(':', substr($image, 0, $type))[1];
    $ext            = explode('/', $type)[1];
    $file_name      = rand().'.'.$ext;

    return $this->addMediaFromBase64($image)
    ->usingName($name)->usingFileName($file_name)
    ->toMediaCollection($collection);
  }
}
