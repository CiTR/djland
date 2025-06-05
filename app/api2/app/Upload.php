<?php


namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Show;
use App\Podcast;
use App\SpecialBroadcast as SpecialBroadcast;
use InvalidArgumentException;
use Illuminate\Support\Facades\Log;

class Upload extends Model
{
  protected $table = 'uploads';
  protected $fillable = array('file_name', 'file_type', 'category', 'path', 'size', 'description', 'url', 'relation_id', 'CREATED_AT', 'UPDATED_AT');

  public static function create(array $attributes = array())
  {
    //Check to see if the file type is acceptable. If not, throw an exception.
    require_once(dirname($_SERVER['DOCUMENT_ROOT']) . "/config.php");
    $allowed_file_types = $djland_upload_categories[$attributes['category']];
    if (!in_array($attributes['file_type'], $allowed_file_types))
      throw new InvalidArgumentException('File Type Not Allowed: ' . $attributes['file_type']);
    return parent::create($attributes);
  }

  public function uploadImage($file)
  {
    require(dirname($_SERVER['DOCUMENT_ROOT']) . "/config.php");
    $response = new \StdClass();

    if ($file == null || $this->category == null) {
      $response->text = "Valid file not given.";
      $response->success = false;
      return $response;
    }

    //Get dirs based on file type
    $base_dir = $_SERVER['DOCUMENT_ROOT'] . "/uploads/";

    //chars to strip from names + dirs
    $strip = array('(', ')', "'", '"', '.', "\\", '/', ',', ':', ';', '@', '#', '$', '%', '&', '?', '!');

    //Ensure the uploads folder exists, if not create it
    if (!file_exists($base_dir)) {
      mkdir($base_dir, 0755);
    }

    //Ensure the category folder exists, if not create it
    $target_dir = $base_dir . $this->category . "/";
    if (!file_exists($target_dir)) {
      mkdir($target_dir, 0775);
    }

    $currtime = time();
    $date_string = date('Y') . '_' . date('m') . '_' . date('d');

    //Ensure the target folder exists, if not create it
    switch ($this->category) {
      case 'show_image':
        $show = Show::find($this->relation_id);
        $target_dir = $target_dir . str_replace($strip, '', $show->name);
        $stripped_name = str_replace($strip, '', $show->name . '_show_image_' . $date_string . '_' . $currtime);
        break;
      case 'episode_image':
        $podcast = Podcast::find($this->relation_id);
        $target_dir = $target_dir . str_replace($strip, '', $podcast->show->name);
        $stripped_name = str_replace($strip, '', $podcast->show->name . '_episode_image_' . $date_string . '_' . $currtime);
        break;
      case 'member_resource':
        $resource = Resource::find($this->relation_id);
        $stripped_name = str_replace($strip, '', $resource->name);
        break;
      case 'special_broadcast_image':
        $special_broadcast = SpecialBroadcast::find($this->relation_id);
        $stripped_name = str_replace($strip, '', $special_broadcast->name);
        break;
      case 'default':
        break;
    }
    $target_dir = str_replace(' ', '_', $target_dir);
    $stripped_name = str_replace(' ', '_', $stripped_name);
    //Generate File Names & Directories
    $today = date('Y-m-d');

    $target_file_name = $stripped_name . "." . $this->file_type;
    $target_file = $target_dir . '/' . $target_file_name;

    //append number if file of same name uploaded.
    $i = 1;
    while (file_exists($target_file)) {
      $target_file_name = $stripped_name . "-" . $i . "." . $this->file_type;
      $target_file = $target_dir . '/' . $target_file_name;
      $i++;
    }

    $target_url = str_replace($_SERVER['DOCUMENT_ROOT'], 'https://' . $_SERVER['SERVER_NAME'], $target_file);

    switch ($this->category) {
      case 'show_image':
        $show = Show::find($this->relation_id)->update(array('image' => $target_url));
        break;
      case 'episode_image':
        $podcast = Podcast::find($this->relation_id);
        $podcast->image = $target_url;
        $podcast->save();
        break;
    }



    if (!is_dir($target_dir)) {
      mkdir($target_dir);
      chmod($target_dir, 0775);
    }

    if ($file->move($target_dir, $target_file_name)) {
      try {
        chmod($target_file, 0775);
        $this->file_name = $target_file_name;
        $this->path = $target_file;
        $this->url = $target_url;
        $this->save();
        return $this;
      } catch (Exception $e) {
        $response->text = "Could not set permissions for file.";
        $response->success = false;
        return $response;
      }
    } else {
      $response->text = "Could not move file to directory.";
      $response->success = false;
      return $response;
    }
  }
  public function uploadAudio($file)
  {
    require(dirname($_SERVER['DOCUMENT_ROOT']) . "/config.php");

    if (!is_object($file)) {
      throw new InvalidArgumentException('Valid file not given.');
    }

    //chars to strip from names + dirs
    $strip = array('(', ')', "'", '"', '.', "\\", '/', ',', ':', ';', '@', '#', '$', '%', '?', '!');


    switch ($this->category) {
      case 'episode_audio':
        $url_base = $url['audio_base'];
        $path_base = $path['audio_base'];
        //Get the podcast
        $podcast = Podcast::find($this->relation_id);

        // Check if podcast is null
        if ($podcast === null) {
          throw new InvalidArgumentException('Podcast not found');

        }

        //Strip unwanted chars from the show name and convert & to and
        $stripped_show_name = str_replace(array('&', ' '), array('and', '-'), str_replace($strip, '', $podcast->show->name));

        //Create the file directory,name, and url
        $target_dir = $path_base . "/" . date('Y', strtotime($podcast->playsheet->start_time));
        if (!file_exists($target_dir))
          mkdir($target_dir, 0775);

        //check if file exists already. If so, we overwrite existing file
        if ($podcast->length && $podcast->length > 0 && $podcast->url != null) {
          $target_file_name = preg_replace('/(.+' . $this->add_slashes(str_replace('https://', '', $url_base)) . '\/)/', '', $podcast->url);
          //Remove the 2017/ 2017/ etc before the file name (the preg_replace above will return 2017/<FILENAME> and not <FILENAME> as we need)
          $target_file_name = basename($target_file_name);
          //the testing enviroment may mean that even though it has a proper url, it still might not exist in our dev path
          //so overwrite it anyway (it's dev, we don't really care too much about overwriting in the test audio base directory)
        } else {
          $target_file_name = $stripped_show_name . "-" . $podcast->id . "-" . $podcast->playsheet->id . "-" . date('F-d-H-i-s', strtotime($podcast->playsheet->start_time)) . '.mp3';
        }

        $target_url = $url_base . '/' . date('Y', strtotime($podcast->playsheet->start_time)) . '/' . $target_file_name;

        break;
      default:
        //we only accepting audio files for episode audio right now.
        $response->text = "Valid audio category was not given.";
        $response->success = false;
        throw new InvalidArgumentException('Valid audio category was not given.');
    }

    if ($file->move($target_dir, $target_dir . '/' . $target_file_name)) {
      $podcast->url = $target_url;
      $podcast->length = $file->getClientSize();
      $podcast->save();
      $response['audio'] = array('url' => $podcast->url, 'length' => $podcast->length);
      $response['xml'] = $podcast->show->make_show_xml();
    } else {
      
      Log::error( 'Failed to move the audio file to ' . $target_dir . '. File name is ' . $target_file_name);

      throw new InvalidArgumentException('Failed to move the audio file ');
    }
    return $response;
  }
  
  public function add_slashes($string)
  {
    return str_replace('/', '\/', $string);
  }
}
