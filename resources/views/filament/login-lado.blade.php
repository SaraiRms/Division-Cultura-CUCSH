{{-- Panel azul del login (solo se dibuja cuando NO hay sesión iniciada) --}}
<div class="login-lado" aria-hidden="true">
    <img src="{{ asset('images/cucsh.svg') }}" alt="" class="login-lado__logo">
    <span class="login-lado__linea"></span>
    <img src="{{ asset('images/Logo_Div_Cultura.svg') }}" alt="" class="login-lado__logo">
    <p class="login-lado__pie">Universidad de Guadalajara</p>
</div>

<style>
    .login-lado { display: none; }

    @media (min-width: 1024px) {
        /* El formulario ocupa la mitad izquierda */
        .fi-simple-layout:has(.login-lado) { width: 50%; }

        /* Sin tarjeta: el formulario va directo sobre el fondo crema */
        .fi-simple-layout:has(.login-lado) .fi-simple-main {
            background: transparent;
            box-shadow: none;
            --tw-ring-shadow: 0 0 #0000;
            border-radius: 0;
        }

        /* El logo del formulario sobra: ya está en el panel azul */
        .fi-simple-layout:has(.login-lado) .fi-logo { display: none; }

        /* La barra vino del pie no se muestra en el login */
        .fi-simple-layout:has(.login-lado) .panel-footer { display: none; }

        /* Panel azul a la derecha, a pantalla completa */
        .login-lado {
            position: fixed; top: 0; right: 0; bottom: 0; width: 50%;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            gap: 2.25rem; padding: 3rem;
            background: #252d49;
        }
        .login-lado__logo { width: min(20rem, 70%); height: auto; filter: brightness(0) invert(1); }
        .login-lado__linea { width: 5rem; height: 1px; background: rgba(255, 255, 255, .35); }
        .login-lado__pie {
            position: absolute; bottom: 2rem; left: 0; right: 0; text-align: center;
            font-size: .8rem; letter-spacing: .2em; text-transform: uppercase; color: rgba(255, 255, 255, .6);
        }
    }

    /* Botón de entrar en color vino (en el login) */
    .fi-simple-layout:has(.login-lado) form button[type="submit"] {
        background-color: #781d26; color: #fff;
    }
    .fi-simple-layout:has(.login-lado) form button[type="submit"]:hover { background-color: #5e1720; }
</style>
