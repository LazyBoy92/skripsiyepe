<script>
    function pilih_jenis() {
      var x = document.getElementById("jenis").value;

      if (x==1) {
      document.getElementById("id_1").innerHTML = '<input type="text" name="id_data"  placeholder="Masukan NIP / NUPTK / KTP" class="form-control" required="required">';
      
      document.getElementById("tombol").innerHTML = '<input type="submit" name="proses"  value="Proses" class="btn btn-primary btn-sm rounded">';    
      }
      else {
       document.getElementById("id_1").innerHTML = '<input type="text" name="id_data"  placeholder="Masukan NIS" class="form-control" required="required">';
     
       document.getElementById("tombol").innerHTML = '<input type="submit" name="proses"  value="Proses" class="btn btn-primary btn-sm rounded">';   
      }
      
    }
</script>
<section class="ftco-section bg-light  ftco-slant ftco-slant-white">
  <div class="container">
    <div class="row">
      <div class="col-md-12 text-center mb-5 ftco-animate">
        <h2 class="text-uppercase ftco-uppercase">Registrasi User</h2>
        <div class="row justify-content-center">
          <div class="col-md-7">
            <p class="lead">Registrasi User Untuk Guru dan Siswa</p>
          </div>
        </div>
      </div>
      <div class="col-lg-12">
        <div class="media d-block mb-0 text-center ftco-media p-md-5 p-4 ftco-animate">
          <div class="ftco-icon mb-3"></div>
          <div class="media-body">
            <form method="POST" action="data-register.html" enctype="multipart/form-data">

              <div class="form-group">
                <select name="jenis" id="jenis" class="form-control" value="" required="required" onchange="pilih_jenis()">
                  <option value=""> Daftar Sebagai..?</option>
                  <option value="1">Guru / Pengajar</option>
                  <option value="2">Siswa</option>
                </select>
              </div>

              <p id="id_1"></p>
              <p id="tombol"></p>

            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
    <!-- END section -->