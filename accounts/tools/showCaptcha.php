<?php
//checa se o gd(extensao) está instalada...
if (!extension_loaded('gd')) 
{
    die("Parece que GD Nao esta instalado...");
}
//pega os chars padrão
session_start();
$iCaptchaLength = 4;
$str_choice = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ2346789';
$str_captcha = '';
//for para o tamanho do captcha no caso é 4
for ($i=0; $i < $iCaptchaLength; $i++) 
{
    do 
    {
        $ipos = rand(0, strlen($str_choice) - 1);
    } while (stripos($str_captcha, $str_choice[$ipos]) !== false);

    $str_captcha .= $str_choice[$ipos];
}
//criação do captcha, seleciona estilos e mais...
$_SESSION['captcha'] = $str_captcha;
$im = imagecreatetruecolor(500, 70);

$bg = imagecolorallocate($im, 255, 255, 255);
imagefill($im, 0, 0, $bg);
for ($i=0; $i < $iCaptchaLength; $i++) 
{
    $text_color = imagecolorallocate($im, rand(0, 100), rand(10, 100), rand(0, 100));
    imagefttext($im, 35, rand(-10, 10), 20 + ($i * 30) + rand(-5, +5), 35 + rand(10, 30), $text_color, 'fonts/AnonymousClippings.ttf', $str_captcha[$i]);
}
header('Content-type: image/png');
header('Pragma: no-cache');
header('Cache-Control: no-store, no-cache, proxy-revalidate');
imagepng($im);
imagedestroy($im);
?>
