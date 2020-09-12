<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
            .text-left{
                float: left;
            }
        </style>
    </head>
    <body>                                  
        <p style="color: black;padding: 8px;text-align: center;">
            <b>Olá,</b> {{$details['nameCustomer']}} solicitou à compra do seguinte produto<br>
            {{$details['descriptionProduct']}}
        </p>
        <div class="flex-center position-ref full-height">            
            <div class="content" style="padding: 15px;background: white;margin-top: -65px; ">                

                <div class="links">                    
                    <img src="{{env('APP_URL')}}/storage/{{$details['attachmentProduct']?$details['attachmentProduct']:'imagens/produto-sem-img.jpg'}}" alt="" style="width: 280px;height: 283px; position: relative;top: 64%;"/><br>                    
                    <span class="text-left" style="text-transform: uppercase;">{{$details['nameProduct']}}</span><br>
                    <span class="text-left">Quantidade: {{$details['quantityProduct']}} / Unidade R$: {{$details['priceProduct']}}</span><br>                                                            
                    <span class="text-left">Forma de pagamento: {{$details['formPayment']}}</span><br>                    
                    <span style="float: left; color: #4285f4;text-transform: uppercase;"><b>Total R$: {{$details['total']}}</b></span><br>
                    <strong class="text-left">Dados do Comprador:</strong><br>
                    <span class="text-left">Nome: {{$details['nameCustomer']}}</span><br>
                    <span class="text-left">Celular: {{$details['phoneCustomer']}}</span><br>
                    <span class="text-left">Endereço:   {{$details['addressCustomer']}}</span><br>
                    <span class="text-left">Cidade:  {{$details['cityCustomer']}}</span><br>
                </div>
            </div>
        </div>
    </body>
</html>

