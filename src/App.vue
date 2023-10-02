<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

const showModal = ref(false);
const newNote = ref("");
const errorMessage = ref("");
const notes = ref([]);
let noteId = 1; 

const getRandomColor = () => {
  const hue = Math.floor(Math.random() * 360);
  const saturation = "100%";
  const lightness = "75%";
  return `hsl(${hue}, ${saturation}, ${lightness})`;
};

const addNote = () => {
  if (newNote.value.length > 150) {
    errorMessage.value = "Note exceeds the limit of 150 characters";
  } else if (newNote.value.length < 10) {
    errorMessage.value = "Note needs to have 10 characters or more";
  } else {
    const newNoteData = {
      nid: noteId++, // Assign a unique id to the new note
      text: newNote.value,
      color: getRandomColor(),
      date: new Date(),
    };

    // Send a POST request to add the note
    axios
      .post("http://localhost:9000/notes/", newNoteData)
      .then(() => {
        // Note added successfully
        notes.value.push(newNoteData);
        showModal.value = false;
        newNote.value = "";
        errorMessage.value = "";
      })
      .catch((error) => {
        console.error("Error adding note:", error);
      });
  }
};
const removeNote = (nid) => {
  const index = notes.value.findIndex(note => note.nid === nid);
  if (index !== -1) {
    notes.value.splice(index, 1);
  }
}

</script>

<template>
  <main>
    <div v-if="showModal" class="overlay">
      <div class="modal">
        <p @click="showModal = false">x</p>
        <textarea v-model.trim="newNote" />
        <h4 class="error" v-if="errorMessage">{{ errorMessage }}</h4>
        <button @click="addNote">Add Note</button>
      </div>
    </div>
    <div class="container">
      <header>
        <h1>Notes</h1>
        <button @click="showModal = true">+</button>
      </header>
      <div class="cards-container">
        <div v-for="note in notes" class="card" :style="{ backgroundColor: note.color }">
          <p class="remove" @click="removeNote(note.nid)">x</p>
          <p class="main-text">{{ note.text }}</p>
          <p class="date">{{ note.date.toLocaleDateString("en-US") }}</p>
        </div>
      </div>
    </div>
  </main>
</template>

<style scoped>
.container {
  max-width: 1000px;
  padding: 10px;
  margin: 0 auto
}

h1 {
  font-weight: bold;
  margin-bottom: 25px;
  font-size: 75px;
}

.card {
  width: 225px;
  height: 225px;
  background-color: rgb(237, 182, 44);
  padding: 10px;
  border-radius: 15px;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  margin-right: 20px;
  margin-bottom: 20px;
  position: relative;
  overflow: hidden; 
}

.main-text {
  line-height: 1.25;
  font-size: 17.5px;
  font-weight: bold;
  word-wrap: break-word; 
  white-space: normal; 
}

.date {
  font-size: 12.5px;
  margin-top: auto;
  font-weight: 500;
}

header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

header button {
  border: none;
  padding: 10px;
  width: 50px;
  height: 50px;
  cursor: pointer;
  background-color: rgb(21, 20, 20);
  border-radius: 1000px;
  color: white;
  font-size: 20px;
  font-weight: bold;

}

.cards-container {
  display: flex;
  flex-wrap: wrap;
}

.overlay {
  position: absolute;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.77);
  transform: translate(-50%, -50%);
  top: 50%;
  left: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 10;
}

main {
  height: 100vh;
  width: 100vw;
}

.modal {
  width: 750px;
  background-color: white;
  border-radius: 10px;
  padding: 30px;
  position: relative;
  display: flex;
  flex-direction: column;
}

.modal button {
  padding: 10px 20px;
  font-size: 20px;
  width: 100%;
  background-color: blueviolet;
  border: none;
  color: white;
  cursor: pointer;
  margin-top: 15px;
}

.modal p {
  margin-left: auto;
  font-size: 25px;
  z-index: 100000;
  cursor: pointer;
  font-weight: 500;
}

.remove {
  position: absolute;
  bottom: 5px;
  right: 5px;
  background-color: transparent;
  color: rgb(70, 52, 52);
  border: none;
  font-size: 20px;
  cursor: pointer;
  font-weight: 600;
  margin-right: 5px;
}


.error {
  color: rgb(248, 0, 0);
  font-size: 14px;
}

textarea {
  width: 100%;
  height: 200px;
  padding: 5px;
  font-size: 20px;
  border: solid 0.5px;
}
</style>