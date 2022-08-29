<template>
  <div class="page">
    <h1>Les bonnes adresses</h1>

    <h2>Vétérinaires</h2>
    <iframe
      src="https://www.google.com/maps?q=veterinaire,76000rouen&output=embed"
      frameborder="0"
    ></iframe>

    <div class="containerAdresse">
      <div>
        <h3>Clinique vétérinaire du Boulingrin</h3>
        <a target="_blank" href="https://www.cliniqueduboulingrin.com/"
          >site Internet</a
        >
        <p>
          <span class="label"> Adresse :</span> <br />
          4 Pl. du Boulingrin, <br />
          76000 <br />
          Rouen
        </p>
        <p>
          <span class="label"> Horaires : </span><br />
          8h30 - 12h / 14h - 19h <br />
          Samedi ferme à 17h <br />
          <span class="red"> Fermé le dimanche</span> <br />
          <br />
        </p>
      </div>
      <img src="../../assets/veterinaireBoulingrin.png" alt="" />
    </div>

    <div class="containerAdresse">
      <div>
        <h3>Clinique Vétérinaire Seinevet Rouen</h3>
        <a target="_blank" href="https://www.seinevet.fr/">site Internet</a>
        <p>
          <span class="label"> Adresse :</span> <br />
          5 Pl. Cauchoise, <br />
          76000 <br />
          Rouen
        </p>
        <p>
          <span class="label"> Horaires : </span><br />
          8h30 - 12h / 14h - 19h <br />
          Samedi ferme à 18h <br />
          <span class="red"> Fermé le dimanche</span> <br />
          <br />
        </p>
      </div>
      <img src="../../assets/veterinaireSeinevet.png" alt="" />
    </div>
  
    <div class="containerAdresse">
      <div>
        <h3>Clinique Vétérinaire Du Faubourg Martainville</h3>
        <a target="_blank" href="http://www.veterinairefm.fr/">site Internet</a>
        <p>
          <span class="label"> Adresse :</span> <br />
          21 Rue du Faubourg Martainville, <br />
          76000 <br />
          Rouen
        </p>
        <p>
          <span class="label"> Horaires : </span><br />
          8h00 - 12h30 / 14h - 19h <br />
          Samedi ferme à 18h <br />
          <span class="red"> Fermé le dimanche</span> <br />
          <br />
        </p>
      </div>
      <img src="../../assets/veterinaire3.png" alt="" />
    </div>
  </div>
</template>

<script>
export default {
  name: "bonnesAdresses",
  data() {
    return {};
  },
  methods: {},
};
</script>
<style scoped src="./style.css"></style>
