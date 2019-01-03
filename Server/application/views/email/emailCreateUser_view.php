<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>{{name_web}}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    </head>
    <body style="margin: 0; padding: 0;">
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td style="padding: 10px 0 30px 0;">
                    <table align="center" border="0" cellpadding="0" cellspacing="0" width="700" style="border: 1px solid #cccccc; border-collapse: collapse;">
                        <tr>
                            <td width="49%" align="right" bgcolor="#61A4FF" style="padding: 20px 0 20px 0; color: #fff; font-size: 28px; font-weight: bold; font-family: Arial, sans-serif;">
                              <img src="<?php echo $this->config->item('logo_thumbnail'); ?>" alt="<?php echo $this->config->item('name_web'); ?>" width="100" />
                            </td>
                            <td align="center" bgcolor="#61A4FF" style="padding: 20px 0px 20px 15px;color: #fff;font-size: 28px;font-weight: bold;font-family: Arial, sans-serif;text-align: left;">
                                Una Llama
                            </td>
                        </tr>
                        <tr>
                            <td bgcolor="#ffffff" style="padding: 40px 30px 0 30px;" colspan="2">
                                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                    <tr>
                                        <td style="color: #153643; font-family: Arial, sans-serif; font-size: 24px;">
                                            <b>Gracias por registrarte en <?php echo $this->config->item('name_web'); ?>!</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 20px 0 30px 0; color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">
                                            Para activa tu cuenta, debes confirmar haciendo <a href="<?php echo $url; ?>">click aqui!</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 5px 0 5px 0; color: #153643; font-family: Arial, sans-serif; font-size: 12px; line-height: 20px;">
                                            Si usted no se registro en nuestra web ignore este mensaje.
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td bgcolor="#61A4FF" style="padding: 30px 30px 30px 30px;" colspan="2">
                                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                    <tr>
                                        <td style="color: #fff; font-family: Arial, sans-serif; font-size: 14px;" width="75%">
                                            Copyright &copy; 2018-<?php echo date('Y'); ?>, <?php echo $this->config->item('name_web'); ?>. All rights reserved.<br />
                                        </td>
                                        <td align="right" width="25%">
                                            <table border="0" cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <td style="font-size: 0; line-height: 0;" width="20">&nbsp;</td>
                                                    <td style="font-family: Arial, sans-serif; font-size: 12px; font-weight: bold;">
                                                        <a href="http://www.facebook.com/<?php echo $this->config->item('fb'); ?>" style="color: #ffffff;">
                                                         <img src="https://cdn1.iconfinder.com/data/icons/iconza-circle-social/64/697057-facebook-48.png" alt="Facebook" width="38" height="38" border="0" style="display: block;" />
                                                        </a>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>