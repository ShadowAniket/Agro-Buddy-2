<script type="module">
  // Import the functions you need from the SDKs you need
  import { initializeApp } from "https://www.gstatic.com/firebasejs/11.6.0/firebase-app.js";
  import { getAnalytics } from "https://www.gstatic.com/firebasejs/11.6.0/firebase-analytics.js";
  // TODO: Add SDKs for Firebase products that you want to use
  // https://firebase.google.com/docs/web/setup#available-libraries

  // Your web app's Firebase configuration
  // For Firebase JS SDK v7.20.0 and later, measurementId is optional
  const firebaseConfig = {
    apiKey: "AIzaSyB07xaJW-ygpwpI3dQu2gqdBz9aqfWYBho",
    authDomain: "agro-buddy-d0267.firebaseapp.com",
    projectId: "agro-buddy-d0267",
    storageBucket: "agro-buddy-d0267.firebasestorage.app",
    messagingSenderId: "569603341143",
    appId: "1:569603341143:web:817580ce8a1c8af4be5c12",
    measurementId: "G-HLS0T6T2ML"
  };

  // Initialize Firebase
  const app = initializeApp(firebaseConfig);
  const analytics = getAnalytics(app);
</script>