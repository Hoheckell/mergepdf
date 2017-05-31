<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use iio\libmergepdf\Merger;
use Validator;
class MergePdfController extends Controller
{
    //

    public function bycsv(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'csv' => 'required:mimetypes:text/plain,text/csv'
            ]);

            if ($validator->fails()) {

                return redirect()->back()->withErrors($validator);
            } else {

                /*
                 * Limpa apasta antes de baixar todos os PDFS
                 */
                $filesnames = glob(public_path('pdfs') . '/*'); // get all file names
                foreach ($filesnames as $nameoffile) { // iterate files
                    if (is_file($nameoffile))
                        unlink($nameoffile); // delete file
                }


                /*
                 * Lê as urls dos arquivos no CSV e baixa um por um
                 */
                $files = array();
                if ($request->hasFile('csv') && $request->file('csv')->isValid()) {
                    $file = $request->file('csv');
                    if (($handle = fopen($file, "r")) !== FALSE) {
                        while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                            $num = count($data);
                            for ($c = 0; $c < $num; $c++) {
                                $name = explode("/", $data[$c]);
                                $output_filename = public_path('pdfs') . "/" . end($name);

                                $host = $data[$c];
                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_URL, $host);
                                curl_setopt($ch, CURLOPT_VERBOSE, 1);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                curl_setopt($ch, CURLOPT_AUTOREFERER, false);
                                // curl_setopt($ch, CURLOPT_REFERER, "http://www.xcontest.org");
                                curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
                                curl_setopt($ch, CURLOPT_HEADER, 0);
                                $result = curl_exec($ch);
                                curl_close($ch);
                                array_push($files, $output_filename);
                                // the following lines write the contents to a file in the same directory (provided permissions etc)
                                $fp = fopen($output_filename, 'w');
                                fwrite($fp, $result);
                                fclose($fp);
                            }
                        }
                        fclose($handle);
                    }
                }

                if(is_array($files) && count($files) > 0){

                    /*
                     * Junta todos os PDFs baixados em um só arquivo e exibe o link
                     */
                    $m = new Merger();
                    $m->addIterator($files);
                    file_put_contents(public_path('pdfs') . "/" . 'merged.pdf', $m->merge());
                    echo '<a href="';
                    echo asset('pdfs/merged.pdf');
                    echo '">Link</a>';
                }else{
                    echo "Falhou, ou arquivo inválido.<br><button onclick='history.back()'>Voltar</button>";
                }



            }
        } catch (\Exception $e) {
            dd($e->getMessage() . " " . $e->getFile() . " " . $e->getLine());
        }

    }
}
