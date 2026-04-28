const EGYPT_CITIES = {
  "Cairo": [
    "Nasr City", "Heliopolis", "Maadi", "Zamalek", "Downtown",
    "Shubra", "Ain Shams", "Mataria", "Mokattam", "New Cairo",
    "6th of October", "Helwan", "Bassatin", "Dar El Salam",
    "El Marg", "Khanka", "Obour", "Badr", "Shorouk"
  ],
  "Giza": [
    "Dokki", "Mohandessin", "Agouza", "Haram", "Faisal",
    "Omrania", "Imbaba", "Bulaq El Dakrour", "Sheikh Zayed",
    "6th of October City", "Kerdasa", "Hadayek El Ahram",
    "Warraq", "Badrashin", "Atfih", "Ausim"
  ],
  "Alexandria": [
    "Smouha", "Gleem", "Roushdy", "Sidi Bishr", "Sporting",
    "Miami", "Montaza", "Mandara", "Agami", "Borg El Arab",
    "Amreya", "Dekheila", "Bab Sharq", "Karmouz", "Mina El Basal",
    "Sidi Gaber", "El Ibrahimeya", "Kafr Abdou"
  ],
  "Dakahlia": [
    "Mansoura", "Talkha", "Mit Ghamr", "Aga", "Bilqas",
    "Dekernes", "El Senbellawein", "Sherbin", "Manzala",
    "Gamasa", "Bani Ebeid", "Temi El Amdid", "Naukratis"
  ],
  "Sharqia": [
    "Zagazig", "10th of Ramadan", "Abu Hammad", "El Husseiniya",
    "Bilbeis", "Mashtoul El Souq", "Minya El Qamh", "Diyarb Negm",
    "El Ibrahimiya", "Kafr Saqr", "Faqous", "Hehia", "Abu Kabir"
  ],
  "Qalyubia": [
    "Banha", "Qalyub", "Shubra El Kheima", "Khanka",
    "El Obour", "Tukh", "Qaha", "Kafr Shukr",
    "El Khusus", "Shibin El Qanatir", "Abu Zaabal"
  ],
  "Gharbia": [
    "Tanta", "El Mahalla El Kubra", "Kafr El Zayat",
    "Zifta", "El Santa", "Basyoun", "Samannoud", "Qutur"
  ],
  "Monufia": [
    "Shibin El Kom", "Menouf", "Ashmoun", "El Bagour",
    "Quesna", "Berket El Sab", "Sadat City", "Tala"
  ],
  "Kafr El Sheikh": [
    "Kafr El Sheikh", "Desouk", "Fuwwah", "El Riyad",
    "Metoubes", "Sidi Salem", "Baltim", "Biula", "Hamoul"
  ],
  "Beheira": [
    "Damanhur", "Kafr El Dawwar", "Rashid", "Edku",
    "Abu El Matamir", "Abu Hummus", "Hosh Issa", "Shubrakhit",
    "El Delengat", "Wadi El Natrun", "Itay El Barud",
    "El Mahmoudiya", "El Rahmaniya", "Kom Hamada"
  ],
  "Ismailia": [
    "Ismailia", "Fayed", "El Qantara", "Abu Suweir",
    "El Tal El Kabir", "El Qassasin"
  ],
  "Port Said": [
    "Port Said", "Port Fouad", "El Zohour", "El Manakh",
    "El Arab", "El Dawahi", "El Sharq", "El Gharb"
  ],
  "Suez": [
    "Suez", "Ain Sokhna", "El Arbaeen", "El Shallofa",
    "Faisal", "El Ganayen"
  ],
  "Damietta": [
    "Damietta", "New Damietta", "Faraskour", "Kafr Saad",
    "El Zarka", "Kafr El Batikh", "El Senbellawein"
  ],
  "North Sinai": [
    "Arish", "Rafah", "Sheikh Zuweid", "Bir El Abd",
    "Hasana", "Nakhl"
  ],
  "South Sinai": [
    "Sharm El Sheikh", "Dahab", "Nuweiba", "Taba",
    "El Tor", "Abu Rudeis", "Saint Catherine"
  ],
  "Red Sea": [
    "Hurghada", "Safaga", "El Quseir", "Marsa Alam",
    "Ras Gharib", "Shalatin", "Halayeb"
  ],
  "New Valley": [
    "Kharga", "Dakhla", "Farafra", "Baris", "Balat"
  ],
  "Matruh": [
    "Marsa Matrouh", "Siwa", "El Salloum", "El Hamam",
    "Alamein", "El Dabaa", "Barani"
  ],
  "Luxor": [
    "Luxor City", "Armant", "Esna", "El Tod",
    "New Tiba", "El Bayadiya", "Qurna"
  ],
  "Aswan": [
    "Aswan", "Kom Ombo", "Edfu", "Daraw",
    "Abu Simbel", "Nasr El Nuba", "Kalabsha"
  ],
  "Asyut": [
    "Asyut", "Abnoub", "Abu Tig", "El Badari",
    "Sahel Selim", "El Fateh", "Qusiya", "Manfalut",
    "Dairut", "El Ghanaim"
  ],
  "Sohag": [
    "Sohag", "Akhmim", "Girga", "El Maragha",
    "El Moansha", "Dar El Salam", "Tahta", "Tima",
    "El Balyana", "Juhayna"
  ],
  "Qena": [
    "Qena", "Luxor", "Nag Hammadi", "Deshna",
    "Abu Tesht", "El Waqf", "Farshout", "Quft", "Qus"
  ],
  "Beni Suef": [
    "Beni Suef", "El Fashn", "Biba", "Sumasta El Waqf",
    "Nasser", "Ihnasiya", "El Wasta"
  ],
  "Faiyum": [
    "Faiyum", "Sinnuris", "El Badrashin", "Ibsheway",
    "Yusuf El Seddiq", "Tamiya"
  ],
  "Minya": [
    "Minya", "Abu Qurqas", "Beni Mazar", "Deir Mawas",
    "El Edwa", "Maghagha", "Mallawi", "Matay", "Samalut"
  ]
};