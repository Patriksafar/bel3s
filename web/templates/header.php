<!-- Compiled and minified JavaScript -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/js/materialize.min.js"></script>
<script src="script/login.js"></script>
<!-- Latest compiled and minified CSS -->
<nav class="user">
<a class="waves-effect waves-light modal-trigger" href="#login">Přihlásit se</a> nebo <a class="waves-effect waves-light modal-trigger" href="#register">registrovat</a>
</nav>

<!-- Modal Structure -->
  <div id="login" class="modal grey lighten-4">
   <div class="modal-content row">
     <img src="pict/logo_bezpozadi.png" alt="branding logo" class="brand">
     <h5>Přihlašte se</h5>
     <form method="post" id="login-form">
       <div class='row'>
         <div class='input-field col s12'>
           <input class='validate' type='email' name='email' id='email' />
           <label for='email'>Váš email</label>
         </div>
       </div>

       <div class='row'>
         <div class='input-field col s12'>
           <input class='validate' type='password' name='password' id='password' />
           <label for='password'>Vaše heslo</label>
         </div>
         <label style='float: right;'>
          <a class='forgot' href='#!'><b>Zapomněl jsem</b></a>
        </label>
       </div>
       <button type="submit" id="btn-login" class="btn amber darken-2 center" name="login">Přihlásit</button>
     </form>
   </div>
 </div>


 <!-- Modal Structure -->
   <div id="register" class="modal grey lighten-4">
    <div class="modal-content row">
      <img src="pict/logo_bezpozadi.png" alt="branding logo" class="brand">
      <h5>Registrovat se</h5>
      <form class="" action="script/register.script.php" method="post">
        <div class='row'>
          <div class='input-field col s12'>
            <input class='validate' type='email' name='email' id='email-reg' />
            <label for='email-reg'>Váš email</label>
          </div>

          <div class='input-field col s12'>
            <input class='validate' type='password' name='password' id='password-reg' />
            <label for='password-reg'>Vaše heslo</label>
          </div>
          <h6>Kontaktní údaje</h6>
          <div class='input-field col s6'>
            <input class='validate' type='text' name='jmeno' id='jmeno' />
            <label for='jmeno'>Jméno</label>
          </div>
          <div class='input-field col s6'>
            <input class='validate' type='text' name='prijmeni' id='prijmeni' />
            <label for='prijmeni'>Příjmení</label>
          </div>
          <div class='input-field col s12'>
            <input class='validate' type='text' name='adresa' id='address' />
            <label for='adresa'>Adresa pro doručení</label>
          </div>
          <div class='input-field col s12'>
            <input class='validate' type='text' name='telefon' id='telefon' value="+420"/>
            <label for='telefon'>Telefon</label>
          </div>
        </div>
        <input type="submit" class="btn amber darken-2 center" name="register" value="Registrovat se">
      </form>
    </div>
  </div>
