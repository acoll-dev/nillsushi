<?php
    
    //$return = shell_exec('git clone http://github.com/acoll-dev/gr-ui ../server/libraries/client/gr-ui 2>&1');
    //echo "<pre>".$return."</pre>";exit;
?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="../server/libraries/client/bootstrap/v3.3.4/css/bootstrap.min.css" />
        <link rel="stylesheet" href="../server/libraries/client/bootstrap/v3.3.4/css/bootstrap.css.map" />
        <link rel="stylesheet" href="css/style.css" />
        <script src="../server/libraries/client/jquery/v2.1.3/jquery-2.1.3.min.js"></script>
        <script src="../server/libraries/client/bootstrap/v3.3.4/js/bootstrap.min.js"></script>
    </head>
    <body>
        <div class="container">
            <div class="container-fluid">
                <img src="images/GRIFFO.svg" id="logo" class="center-block"/>
            </div>
            <div class="jumbotron" id="content-jumbotron">
                <h2>Bem vindo ao Griffo Framework!</h2>
                <p>Essa é a tela de instalação do Griffo Framework, abaixo contém as configurações necessárias para o funcionamento da ferramenta.</p>
            </div>

            <!--Configuração do banco de dados-->  

            <div class="panel panel-default">

                <div class="panel-heading">Configuração do Banco de dados</div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1">Servidor</span>
                            <input type="text" class="form-control" placeholder="ex: localhost" aria-describedby="basic-addon1">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1">Usuário</span>
                            <input type="text" class="form-control" placeholder="ex: admin" aria-describedby="basic-addon1">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1">Senha</span>
                            <input type="text" class="form-control" aria-describedby="basic-addon1">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1">Banco de dados</span>
                            <input type="text" class="form-control" placeholder="ex: griffo" aria-describedby="basic-addon1">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1">Prefixo</span>
                            <input type="text" class="form-control" placeholder="ex: gr_" aria-describedby="basic-addon1">
                        </div>
                    </div>
                </div>
            </div>

            <!--Configuração do cliente-->

            <div class="panel panel-default">
                <div class="panel-heading">Configuração do Cliente</div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1">Nome</span>
                            <input type="text" class="form-control" placeholder="ex: acoll assessoria e comunicação" aria-describedby="basic-addon1">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1">Website</span>
                            <input type="text" class="form-control" placeholder="ex: www.acoll.com.br" aria-describedby="basic-addon1">
                        </div>
                    </div>
                </div>
            </div>

            <!--Configuração das imagens-->

            <div class="panel panel-default">
                <div class="panel-heading">Configuração das Imagens</div>
                <div class="panel-body">
                    <div class="form-group">
                        <h4>Thumb</h4>
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1">Largura</span>
                            <input type="text" class="form-control" placeholder="ex: 300" aria-describedby="basic-addon1">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1">Altura</span>
                            <input type="text" class="form-control" placeholder="ex: 200" aria-describedby="basic-addon1">
                        </div>
                    </div>

                    <hr/>

                    <div class="form-group">
                        <h4>Taxa de Compressão</h4>
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="ex: 50" aria-describedby="basic-addon2">
                            <span class="input-group-addon" id="basic-addon2">%</span>
                        </div>
                    </div>
                </div>
            </div>

            <!--Configuração dos alias-->

            <div class="panel panel-default">
                <div class="panel-heading">Configuração dos Alias</div>
                <div class="panel-body">
                    <div class="form-group">
                        <form>
                            <div class="input-group">
                                <span class="input-group-addon" id="basic-addon1">Url</span>
                                <input type="text" class="form-control" placeholder="ex: localhost/griffo" aria-describedby="basic-addon2" id="url">
                                <span class="input-group-addon" id="basic-addon1">Camada</span>
                                <select class="form-control" id="layer">
                                    <option value="admin">Admin</option>
                                    <option value="website">Website</option>
                                </select>
                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-primary" id="btn-cad" onclick="Save()">Cadastrar</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="form-group">
                        <div class="input-group">
                            <table class="table table-striped table-bordered" id="table-alias">
                                <thead>
                                    <tr>
                                        <th>Alias</th><th>Layer</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-success">Concluir</button>
            </div>

            <div class="container-fluid" id="footer">
                <img src="images/griffo-footer-black.png" class="center-block"/>
            </div>
        </div>
    </body>
    <script>
        
        function Save(){
            
            var par = $(this).parent().parent();
            var tdAlias = par.children("td:nth-child(1)");
            var tdLayer = par.children("td:nth-child(2)");

            alert(document.getElementById(#));
            tdAlias.html(tdAlias.children("input[type=text]").val());
            tdLayer.html(tdLayer.children("input[type=select]").val());
        };
    </script>
</html>