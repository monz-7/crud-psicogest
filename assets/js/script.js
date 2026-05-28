// INPUT DE TELÉFONO
let iti;

document.addEventListener("DOMContentLoaded", () => {
  const phoneInput = document.getElementById("phone");

  iti = window.intlTelInput(phoneInput, {
    initialCountry: "co",
    separateDialCode: true,
    nationalMode: false,
  });
});

// INICIALIZA VALIDACIONES DEL FORMULARIO

document.addEventListener("DOMContentLoaded", () => {
  const birthdateInput = document.getElementById("birthdate");
  const form = document.querySelector("form");

  if (!birthdateInput || !form) return;

  setupDateLimits(birthdateInput);
  form.addEventListener("submit", (e) => handleFormSubmit(e, birthdateInput));
});

// CONFIGURA RANGO DE EDAD (edad entre 5 y 100 años)
function setupDateLimits(input) {
  const today = new Date();

  const maxDate = new Date();
  maxDate.setFullYear(today.getFullYear() - 5);

  const minDate = new Date();
  minDate.setFullYear(today.getFullYear() - 100);

  input.max = formatDate(maxDate);
  input.min = formatDate(minDate);
}

// FORMATEA LA FEHCA A YYYY-MM-DD (evita problemas de timezone)
function formatDate(date) {
  return date.toISOString().split("T")[0];
}

// MANEJA EL ENVIO DEL FORMULARIO
function handleFormSubmit(event, birthdateInput) {
  // NORMALIZA EL TELÉFONO INTERNACIONAL
  setFullPhoneNumber();

  // VALIDA QUE COINCIDAN LOS CORREOS
  const email = document.getElementById("email").value;
  const emailConfirm = document.getElementById("email-confirm").value;

  if (email !== emailConfirm) {
    event.preventDefault();
    alert("Los correos electrónicos no coinciden.");
    return;
  }

  // VALIDA QUE COINCIDAN LAS CONTRASEÑAS
  const password = document.getElementById("password").value;
  const passwordConfirm = document.getElementById("password-confirm").value;

  if (password !== passwordConfirm) {
    event.preventDefault();
    alert("Las contraseñas no coinciden.");
    return;
  }

  // VALIDA LA EDAD
  const age = calculateAge(new Date(birthdateInput.value));

  if (age < 5) {
    event.preventDefault();
    alert("El paciente debe tener mínimo 5 años.");
    return;
  }

  if (age > 100) {
    event.preventDefault();
    alert("El paciente no puede tener más de 100 años.");
    return;
  }
}

// CALCULA LA EDAD SEGÚN EL MES Y DÍA ACTUALES
function calculateAge(birthDate) {
  const today = new Date();

  let age = today.getFullYear() - birthDate.getFullYear();

  const monthDiff = today.getMonth() - birthDate.getMonth();

  // Ajuste si aún no ha cumplido años este año
  if (
    monthDiff < 0 ||
    (monthDiff === 0 && today.getDate() < birthDate.getDate())
  ) {
    age--;
  }

  return age;
}

// OBTIENE EL NÚMERO COMPLETO DE TELÉFONO DESDE EL INTL-TEL-INPUT (LIBRERÍA)
function setFullPhoneNumber() {
  if (typeof iti !== "undefined") {
    const fullPhone = iti.getNumber();
    const hiddenInput = document.getElementById("phone-full");

    if (hiddenInput) {
      hiddenInput.value = fullPhone;
    }
  }
}

// SELECT DE PAÍSES

let countryChoices;

document.addEventListener("DOMContentLoaded", loadCountries);

async function loadCountries() {
  const select = document.getElementById("country");

  try {
    const response = await fetch(
      "https://restcountries.com/v3.1/independent?status=true",
    );

    const countries = await response.json();

    // Placeholder
    select.innerHTML = `
      <option value="" disabled selected hidden>
        Selecciona el país
      </option>
    `;

    // Excluye Colombia y ordena
    const filteredCountries = countries
      .filter((country) => country.cca2 !== "CO")
      .sort((a, b) => {
        const nameA = a.translations.spa?.common || a.name.common;

        const nameB = b.translations.spa?.common || b.name.common;

        return nameA.localeCompare(nameB);
      });

    // Crear opciones
    filteredCountries.forEach((country) => {
      const option = document.createElement("option");

      // Nombre del país
      const countryName =
        country.translations.spa?.common || country.name.common;

      // Código del país
      const countryCode = country.cca2.toLowerCase();

      // URL para poner banderas de los países SVG
      const countryFlag = `https://flagcdn.com/w40/${countryCode}.png`;

      option.value = countryName;

      // Guardar propiedades custom
      option.dataset.customProperties = JSON.stringify({
        flag: countryFlag,
      });

      option.textContent = countryName;

      select.appendChild(option);
    });

    // Inicializa el Choices.js (select de países)
    countryChoices = new Choices(select, {
      searchEnabled: true,
      itemSelectText: "",
      shouldSort: false,
      searchPlaceholderValue: "Buscar país",

      callbackOnCreateTemplates: function (template) {
        return {
          // Elemento seleccionado
          item: ({ classNames }, data) => {
            if (data.placeholder) {
              return template(`
            <div
              class="${classNames.item} ${classNames.placeholder}"
              data-item
              data-id="${data.id}"
              data-value=""
            >
              ${data.label}
            </div>
          `);
            }

            const flag = data.customProperties?.flag || "";

            return template(`
            <div
              class="${classNames.item} ${
                data.highlighted
                  ? classNames.highlightedState
                  : classNames.itemSelectable
              }"
              data-item
              data-id="${data.id}"
              data-value="${data.value}"
            >
              <img class="country-flag" src="${flag}" alt="">
              ${data.label}
            </div>
          `);
          },

          // Opciones del dropdown
          choice: ({ classNames }, data) => {
            const flag = data.customProperties?.flag || "";

            return template(`
              <div
                class="${classNames.item}
                ${classNames.itemChoice}
                ${
                  data.disabled
                    ? classNames.itemDisabled
                    : classNames.itemSelectable
                }"
                data-choice
                data-id="${data.id}"
                data-value="${data.value}"
                role="option"
              >
                <img class="country-flag" src="${flag}" alt="">
                ${data.label}
              </div>
            `);
          },
        };
      },
    });
  } catch (error) {
    select.innerHTML = `
      <option disabled selected>
        Error cargando países
      </option>
    `;

    console.error("Error cargando países:", error);
  }
}

// UI (Navegación y ubicación)

// Espera a que el DOM esté listo
document.addEventListener("DOMContentLoaded", () => {
  initPasswordToggle();
  initNavigation();
  initLocationSelector();
  initSelectBlur();
});

// Alterna visibilidad de contraseñas
function initPasswordToggle() {
  const toggleButtons = document.querySelectorAll(".toggle-password");

  toggleButtons.forEach((button) => {
    button.addEventListener("click", () => {
      // Buscar el input dentro del mismo contenedor (más robusto)
      const input = button.closest(".field").querySelector("input");

      const isPassword = input.type === "password";

      input.type = isPassword ? "text" : "password";
      button.style.backgroundImage = isPassword
        ? "var(--eye-closed-icon)"
        : "var(--eye-open-icon)";
    });
  });
}

// Maneja navegación entre el formulario (CREATE) y la tabla (READ)
function initNavigation() {
  const btnRegister = document.getElementById("btn-register");
  const btnPatients = document.getElementById("btn-patients");

  const formSection = document.getElementById("form-section");
  const patientsSection = document.getElementById("patients-section");

  const pageTitle = document.getElementById("page-title");

  btnRegister.addEventListener("click", () => {
    toggleView(true);

    pageTitle.textContent = "FORMULARIO DE REGISTRO DE PACIENTES";

    btnRegister.classList.add("active");
    btnPatients.classList.remove("active");
  });

  btnPatients.addEventListener("click", () => {
    toggleView(false);

    pageTitle.textContent = "PACIENTES REGISTRADOS";

    btnPatients.classList.add("active");
    btnRegister.classList.remove("active");
  });

  // Muestra u oculta secciones
  const formContainer = document.querySelector(".form-container");

  function toggleView(showForm) {
    formContainer.style.display = showForm ? "flex" : "none";
    patientsSection.style.display = showForm ? "none" : "block";
  }
}

// Maneja la lógica de ubicación (Colombia vs otro país)
function initLocationSelector() {
  const locationRadios = document.querySelectorAll('input[name="location"]');
  const countryWrapper = document.getElementById("country-select-wrapper");
  const countrySelect = document.getElementById("country");

  locationRadios.forEach((radio) => {
    radio.addEventListener("change", () => {
      const isOtherCountry = radio.value === "otro" && radio.checked;

      if (isOtherCountry) {
        countryWrapper.style.opacity = "1";
        countryWrapper.style.pointerEvents = "auto";
        countryWrapper.style.transform = "translateY(0)";
      } else {
        countryWrapper.style.opacity = "0";
        countryWrapper.style.pointerEvents = "none";
        countryWrapper.style.transform = "translateY(-5px)";
      }
      countrySelect.required = isOtherCountry;

      // Reset si cambia a Colombia
      if (!isOtherCountry) {
        if (countryChoices) {
          countryChoices.removeActiveItems();
        }

        countrySelect.value = "";
      }
    });
  });
}

// Quita el focus de los select al cambiar
function initSelectBlur() {
  document.querySelectorAll("select").forEach((select) => {
    select.addEventListener("change", () => select.blur());
  });
}

// CRUD

function getFormElements() {
  return {
    form: document.querySelector("form"),
    btnCancel: document.getElementById("btn-cancel"),
    btnSubmit: document.querySelector(".btn-submit"),
    pageTitle: document.getElementById("page-title"),
    countryWrapper: document.getElementById("country-select-wrapper"),
  };
}

// UPDATE

// Activa el modo edición del formulario

function editPatient(
  uuid,
  docType,
  docNumber,
  names,
  surnames,
  birthdate,
  phone,
  email,
  location,
) {
  // Navega al formulario
  document.getElementById("btn-register").click();

  // Rellena campos según el paciente por el UUID
  document.getElementById("uuid").value = uuid;
  document.getElementById("doctype").value = docType;
  document.getElementById("docnumber").value = docNumber;
  document.getElementById("names").value = names;
  document.getElementById("surnames").value = surnames;
  document.getElementById("birthdate").value = birthdate;
  document.getElementById("email").value = email;
  document.getElementById("email-confirm").value = email;

  const passwordInput = document.getElementById("password");
  const passwordConfirmInput = document.getElementById("password-confirm");

  passwordInput.value = "********"; // para que se llene visualmente con algo
  passwordConfirmInput.value = "********";

  passwordInput.required = false;
  passwordConfirmInput.required = false;

  // Bloquear edición de la contraseña
  passwordInput.disabled = true;
  passwordConfirmInput.disabled = true;

  // Manejo del teléfono (intl-tel-input)
  if (typeof iti !== "undefined") {
    iti.setNumber(phone);
  }

  // Manejo de la ubicación
  handleLocation(location);

  // Cambia a modo UPDATE
  const { form, btnSubmit, btnCancel } = getFormElements();

  const btnRegister = document.getElementById("btn-register");

  form.action = "update.php";
  btnSubmit.textContent = "ACTUALIZAR PACIENTE";
  btnRegister.textContent = "ACTUALIZAR PACIENTE REGISTRADO";

  // Muestra botón de cancelar
  btnCancel.style.display = "inline-flex";

  // Actualiza el título en el header
  document.getElementById("page-title").textContent =
    "ACTUALIZACIÓN DE DATOS DEL PACIENTE";
}

// Gestiona la selección de ubicación
function handleLocation(location) {
  const countrySelect = document.getElementById("country");
  const countryWrapper = document.getElementById("country-select-wrapper");

  if (location.toLowerCase() === "colombia") {
    document.querySelector('input[value="colombia"]').checked = true;

    countryWrapper.style.opacity = "0";
    countryWrapper.style.pointerEvents = "none";
    countryWrapper.style.transform = "translateY(-5px)";

    countrySelect.required = false;

    if (countryChoices) {
      countryChoices.removeActiveItems();
    }

    countrySelect.value = "";
  } else {
    document.querySelector('input[value="otro"]').checked = true;

    countryWrapper.style.opacity = "1";
    countryWrapper.style.pointerEvents = "auto";
    countryWrapper.style.transform = "translateY(0)";
    countryWrapper.style.maxHeight = "100px";

    countrySelect.required = true;

    if (countryChoices) {
      countryChoices.setChoiceByValue(location);
    }
  }
}

// Restaura el formulario a modo registro (CREATE)
function resetFormToCreateMode() {
  const { form, btnSubmit, btnCancel, countryWrapper } = getFormElements();

  form.reset();

  // Limpia radios de ubicación
  document.querySelectorAll('input[name="location"]').forEach((radio) => {
    radio.checked = false;
  });

  // Limpia Choices.js (select de países)
  if (countryChoices) {
    countryChoices.removeActiveItems();
  }
  document.getElementById("country").value = "";

  // Resetea el teléfono
  if (typeof iti !== "undefined") {
    iti.setNumber("");
  }

  // Restaura la configuración original
  form.action = "insert.php";
  btnSubmit.textContent = "REGISTRAR PACIENTE";

  document.getElementById("btn-register").textContent =
    "REGISTRAR NUEVO PACIENTE";

  // Restaura UI
  document.getElementById("page-title").textContent =
    "FORMULARIO DE REGISTRO DE PACIENTES";
  countryWrapper.style.opacity = "0";
  countryWrapper.style.pointerEvents = "none";
  countryWrapper.style.transform = "translateY(-5px)";
  btnCancel.style.display = "none";

  // Restaura required
  document.getElementById("password").required = true;
  document.getElementById("password-confirm").required = true;
  document.getElementById("password").disabled = false;
  document.getElementById("password-confirm").disabled = false;
  document.getElementById("country").required = false;

  // Limpia UUID (para evitar updates accidentales)
  document.getElementById("uuid").value = "";

  // Vuelve a la tabla de pacientes
  document.getElementById("btn-patients").click();
}

// Inicializa eventos del módulo
document.addEventListener("DOMContentLoaded", () => {
  const btnCancel = document.getElementById("btn-cancel");

  if (btnCancel) {
    btnCancel.addEventListener("click", resetFormToCreateMode);
  }
});

// DELETE

// Elimina un paciente previa confirmación
function deletePatient(uuid) {
  const confirmed = confirm("¿Seguro que quieres eliminar este paciente?");

  if (!confirmed) return;

  // Redirecciona al endpoint de eliminación
  window.location.href = `delete.php?uuid=${encodeURIComponent(uuid)}&view=patients`;
}

// ABRE AUTOMATICAMENTE LA TABLA SI SE VIENE DESDE DELETE / UPDATE
document.addEventListener("DOMContentLoaded", () => {
  const params = new URLSearchParams(window.location.search);

  if (params.get("view") === "patients") {
    document.getElementById("btn-patients").click();
  }
});
