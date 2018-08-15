<?php
namespace JensTornell\ComponentKit;
use Response;
use tpl;
use Exception;
use c;


$assets = 'kirby-components/assets';

if(c::get('plugin.components.assets.path', 'kirby-components/assets')) {
    kirby()->set('route', [
        'pattern' => $assets . '/(:all)/(:any)',
        'method'  => 'GET',
        'action'  => function($id, $filename) {
            $Assets = new Assets();

            $whitelist = $Assets->whitelist();

            $Helpers = new Helpers();
            $roots = $Helpers->roots();

            foreach($roots as $root) {
                if(is_string($root)) {
                    $filepath = "$root/$id/$filename";
                } elseif(is_array($root)) {
                    $new_id = substr($id, strlen(key($root)));
                    $root = array_values($root)[0];
                    $filepath = "$root/$new_id/$filename";
                }

                $extension = pathinfo($filepath, PATHINFO_EXTENSION);
                $error = new Response('The file could not be found', $extension, 404);

                if(!in_array($extension, $whitelist)) continue;
                if(!file_exists($filepath)) continue;

                return new Response(file_get_contents($filepath), $extension);
            }

            return $error;
        }
    ]);
}

kirby()->set('route', [
    'pattern' => 'c/(:all)',
    'method'  => 'GET',
    'action'  => function($component) {
        $SnippetPreview = new SnippetPreview(kirby());
        $Helpers = new Helpers();

        $roots = $Helpers->roots();

        foreach($roots as $root) {
            $root = is_array($root) ? array_values($root)[0] : $root;
            $new_roots[] = $root;
        }

        $roots = $new_roots;

        $page = page('home');
        
        foreach($roots as $path) {
            $component_dirpath = $path . '/' . $component;
            if(file_exists($component_dirpath)) {
                $options_path = $component_dirpath . '/config.php';

                $Snippet = new Snippet();
                $type = $Snippet->type($component);
                $values = [];

                if(file_exists($options_path)) {
                    $options = include $options_path;
                    if(isset($options['page'])) {
                        $page = $options['page'];
                    }

                    if(isset($options['values'])) {
                        $values = $options['values'];
                    }
                }

                $filename = (file_exists($component_dirpath . '/preview.php')) ? 'preview.php' : 'component.php';

                $data = $SnippetPreview->render(
                    $component_dirpath . '/' . $filename,
                    $values,
                    $page
                );

                if($type == 'snippet') {
                    echo c::get('plugin.components.header', snippet('component-header', ['dirpath' => $component_dirpath], true));
                    echo $data;
                    echo c::get('plugin.components.footer', snippet('component-footer', [], true));
                } else {
                    echo $data;
                }
            }
        }
       
    }
]);

class SnippetPreview extends \Kirby\Component\Template {
    public function render($filepath, $data = [], $page = null) {
        $data = $this->data($page, $data);

        if(!file_exists($filepath)) {
            throw new Exception("The template $filepath could not be found");
        }

        $tplData = tpl::$data;
        tpl::$data = array_merge(tpl::$data, $data);
        $result = tpl::load($filepath, null);
        tpl::$data = $tplData;

        return $result;
    }
}

class Assets {
    function whitelist() {
        // https://getkirby.com/docs/cheatsheet/file/type

        return [
            // Image
            'jpg',
            'jpeg',
            'gif',
            'png',
            'svg',
            'ico',
            'tiff',
            'bmp',
            'psd',
            'ai',
            
            // Document
            'md',
            'pdf',
            'doc',
            'docx',
            'xls',
            'xlsx',
            'ppt',
            'csv',
            'rtf',

            // Archive
            'zip',
            'tar',
            'gz',
            'gzip',
            'tgz',
            
            // Code
            'js',
            'css',
            'html',
            'xml',
            'json',
            
            // Video
            'mov',
            'avi',
            'ogg',
            'ogv',
            'webm',
            'flv',
            'swf',
            'mp4',
            'mv4',

            // Audio
            'mp3',
            'm4a',
            'wav',
            'aiff',
            'midi',
        ];
    }
}