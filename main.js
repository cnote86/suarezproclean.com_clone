/* ============================================================
   FOOTER YEAR HANDLER
   ============================================================ */
// Dynamically set current year in the footer
document.addEventListener("DOMContentLoaded", () => {
  const y = document.getElementById("year");
  if (y) y.textContent = new Date().getFullYear();
});


/* ============================================================
   QUOTE FORM HANDLING & VALIDATION
   ============================================================ */
const form = document.getElementById("quoteForm");
if (form) {
  const status = document.getElementById("formStatus");

  // ------------------------------------------------------------
  // Helper: display or clear validation error messages
  // ------------------------------------------------------------
  const setError = (el, msg) => {
    const small = el.parentElement.querySelector(".error");
    if (small) small.textContent = msg || "";
  };

  // ------------------------------------------------------------
  // Field Validators (return true if valid, or error string)
  // ------------------------------------------------------------
  const validators = {
    name: v => v.trim().length >= 2 || "Please enter your full name.",
    phone: v => /\d{7,}/.test(v.replace(/\D/g, "")) || "Enter a valid phone number.",
    email: v => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v) || "Enter a valid email.",
    city: v => v.trim().length >= 2 || "Enter your city.",
    service: v => !!v || "Select a service type.",
    consent: v => v === true || "Please consent so we can text/call about your quote."
  };


  /* ============================================================
     FORM SUBMISSION
     ============================================================ */
  form.addEventListener("submit", async (e) => {
    e.preventDefault();
    status.textContent = "";
    let ok = true;

    // Collect form data
    const fd = new FormData(form);
    const data = {
      name: fd.get("name")?.toString().trim() || "",
      company: fd.get("company")?.toString().trim() || "",
      phone: fd.get("phone")?.toString().trim() || "",
      email: fd.get("email")?.toString().trim() || "",
      city: fd.get("city")?.toString().trim() || "",
      contactTime: fd.get("contactTime")?.toString() || "",
      service: fd.get("service")?.toString() || "",
      size: fd.get("size")?.toString().trim() || "",
      message: fd.get("message")?.toString().trim() || "",
      consent: fd.get("consent") === "on"
    };

    // Validate key fields
    ["name","phone","email","city","service"].forEach(name => {
      const input = form.querySelector(`[name="${name}"]`);
      const valid = validators[name](data[name]);
      if (valid !== true) { 
        ok = false; 
        setError(input, valid); 
      } else { 
        setError(input, ""); 
      }
    });

    // Validate consent checkbox separately
    const consentInput = form.querySelector('[name="consent"]');
    const consentValid = validators.consent(data.consent);
    if (consentValid !== true) {
      ok = false;
      consentInput.focus();
      status.textContent = "Please fix the errors above.";
    }

    if (!ok) return;

    // ------------------------------------------------------------
    // SUBMIT TO PHP HANDLER (send-form.php)
    // ------------------------------------------------------------
    try {
      status.textContent = "Sending…";
      const res = await fetch("send-form.php", {
        method: "POST",
        body: new URLSearchParams(data) // send as form-urlencoded
      });
      if (!res.ok) throw new Error(await res.text());

      status.textContent = "Thanks! We'll text you shortly to confirm details.";
      form.reset();
    } catch (err) {
      console.error(err);
      status.textContent = "Something went wrong. Please try again or call us.";
    }
  });


  /* ============================================================
     LIVE FIELD VALIDATION (on input change)
     ============================================================ */
  form.addEventListener("input", (e) => {
    const target = e.target;
    if (!(target instanceof HTMLElement)) return;

    const name = target.getAttribute("name");
    if (!name || !validators[name]) return;

    const val = (target instanceof HTMLInputElement || 
                 target instanceof HTMLTextAreaElement || 
                 target instanceof HTMLSelectElement) 
                 ? target.value : "";

    const valid = validators[name](name === "consent" ? target.checked : val);
    setError(target, valid === true ? "" : valid);
  });
}
