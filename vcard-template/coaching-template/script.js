const state = {
    data: window.__ACTION_DATA__ || null,
    selectedDemoSlot: "",
};

const textTemplate = (template, vars) =>
    String(template || "").replace(/\{\{(\w+)\}\}/g, (_, key) =>
        vars[key] == null ? "" : String(vars[key]),
    );

const getWebsite = () => state.data?.shop?.website || window.location.href;

const getSubmissionUrl = (type) => {
    if (window.__VCARD_SUBDOMAIN__) {
        return `/vcard/${window.__VCARD_SUBDOMAIN__}/submit/${type}`;
    }

    const hostParts = window.location.hostname.split(".");
    const pathParts = window.location.pathname.split("/").filter(Boolean);

    if (hostParts.length > 2) {
        return `/submit/${type}`;
    }

    if (pathParts.length > 0) {
        if (pathParts[0] === "vcard" && pathParts.length > 1) {
            return `/vcard/${pathParts[1]}/submit/${type}`;
        }
        return `/vcard/${pathParts[0]}/submit/${type}`;
    }

    return `/submit/${type}`;
};

const sendSubmission = (type, payload) => {
    const csrfToken =
        document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute("content") || "";
    return fetch(getSubmissionUrl(type), {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-Requested-With": "XMLHttpRequest",
            ...(csrfToken && { "X-CSRF-TOKEN": csrfToken }),
        },
        body: JSON.stringify(payload),
    })
        .then(async (res) => {
            const data = await res.json();
            if (!res.ok) {
                console.error(
                    `Submission ${type} failed with status ${res.status}:`,
                    data,
                );
                return { success: false, ...data };
            }
            return data;
        })
        .catch((err) => {
            console.error("Submission error:", err);
            return { success: false, message: err.message };
        });
};

const animateCounter = (id, target, suffix = "", duration = 1500) => {
    const el = document.getElementById(id);
    if (!el) return;

    let current = 0;
    const step = target / (duration / 16);
    const timer = setInterval(() => {
        current += step;
        if (current >= target) {
            current = target;
            clearInterval(timer);
        }
        el.textContent = `${Math.floor(current)}${suffix}`;
    }, 16);
};

const selectDemo = (el) => {
    document
        .querySelectorAll(".demo-slot")
        .forEach((slot) => slot.classList.remove("selected"));
    el.classList.add("selected");
    state.selectedDemoSlot = el.dataset.slot || "";
};

const isValidPhone = (phone) => /^[+]?[\d\s\-()]{7,15}$/.test(phone);

const bookDemo = async () => {
    if (!state.data) return;

    const name = document.getElementById("dName")?.value.trim();
    const phone = document.getElementById("dPhone")?.value.trim();

    if (!name) {
        showToast(state.data.toast?.nameRequired || "Please enter your name!");
        return;
    }
    if (!isValidPhone(phone || "")) {
        showToast(
            state.data.toast?.phoneInvalid ||
                "Please enter a valid phone number!",
        );
        return;
    }

    const exam = document.getElementById("dExam")?.value || "";
    const edu = document.getElementById("dEdu")?.value || "";
    const attempt =
        document.getElementById("dAttempt")?.value.trim() || "First attempt";

    const header = state.data.messages?.demoHeader || "Free Demo Registration";
    const confirm =
        state.data.messages?.demoConfirm ||
        "Kindly confirm my demo class. Thank you!";
    const slot = state.selectedDemoSlot || "";

    const msg =
        `\uD83C\uDF93 *${header} – ${state.data.shop?.name || ""}*\n\n` +
        `\uD83D\uDC64 Name: ${name}\n` +
        `\uD83D\uDCDE Phone: ${phone}\n` +
        `\uD83C\uDFAF Target Exam: ${exam}\n` +
        `\uD83C\uDF93 Education: ${edu}\n` +
        `\uD83D\uDCC5 Preferred Slot: ${slot}\n` +
        `\uD83D\uDCCB Attempts: ${attempt}\n\n${confirm}`;

    await sendSubmission("booking", {
        source_template: state.data?.meta?.title || "coaching-template",
        shop_name: state.data.shop?.name || "",
        name,
        phone,
        email: "",
        message: "Demo class registration",
        items: [
            { label: "exam", value: exam },
            { label: "education", value: edu },
            { label: "slot", value: slot },
            { label: "attempts", value: attempt },
        ],
    });

    window.open(
        `https://wa.me/${state.data.shop?.whatsapp}?text=${encodeURIComponent(msg)}`,
        "_blank",
    );

    document.getElementById("demoForm")?.style &&
        (document.getElementById("demoForm").style.display = "none");
    document.getElementById("demoSuccess")?.style &&
        (document.getElementById("demoSuccess").style.display = "block");
};

const resetDemo = () => {
    const form = document.getElementById("demoForm");
    const success = document.getElementById("demoSuccess");
    if (form) form.style.display = "block";
    if (success) success.style.display = "none";
    ["dName", "dPhone", "dAttempt"].forEach((id) => {
        const field = document.getElementById(id);
        if (field) field.value = "";
    });
};

const toggleFaq = (el) => {
    const item = el.parentElement;
    const wasOpen = item.classList.contains("open");
    document
        .querySelectorAll(".faq-item")
        .forEach((i) => i.classList.remove("open"));
    if (!wasOpen) item.classList.add("open");
};

const callInstitute = () => {
    if (!state.data) return;
    window.location.href = `tel:${state.data.shop?.phone || ""}`;
};

const openWA = () => {
    if (!state.data) return;
    const text = state.data.messages?.waEnquiry || "";
    window.open(
        `https://wa.me/${state.data.shop?.whatsapp}?text=${encodeURIComponent(text)}`,
        "_blank",
    );
};

const emailInstitute = () => {
    if (!state.data) return;
    window.location.href = `mailto:${state.data.shop?.email || ""}`;
};

const openMaps = () => {
    if (!state.data) return;
    window.open(state.data.shop?.maps || "", "_blank");
};

const enquireWA = (topic) => {
    if (!state.data) return;
    const text = `Hi! I want to enquire about: *${topic}* at ${state.data.shop?.name || ""}.`;
    window.open(
        `https://wa.me/${state.data.shop?.whatsapp}?text=${encodeURIComponent(text)}`,
        "_blank",
    );
};

const saveContact = () => {
    if (!state.data) return;

    const shop = state.data.shop || {};
    const vcard = `BEGIN:VCARD\nVERSION:3.0\nFN:${shop.name || ""}\nTITLE:Competitive Exam Coaching Institute\nORG:${shop.name || ""}\nTEL;TYPE=CELL:${shop.phone || ""}\nEMAIL:${shop.email || ""}\nADR:;;${shop.address || ""};;;;\nURL:${getWebsite()}\nNOTE:${shop.tagline || ""} | Reg. ${shop.registrationId || ""} | Est. ${shop.established || ""}\nEND:VCARD`;
    const a = document.createElement("a");
    a.href = URL.createObjectURL(new Blob([vcard], { type: "text/vcard" }));
    a.download = shop.vcardFileName || "Contact.vcf";
    a.click();
    showToast(state.data.toast?.contactSaved || "Contact saved!");
};

const genQR = () => {
    if (!state.data || typeof QRCode === "undefined") return;

    const el = document.getElementById("instituteQR");
    if (!el) return;
    el.innerHTML = "";

    new QRCode(el, {
        text: getWebsite(),
        width: 165,
        height: 165,
        colorDark: "#0f1d3a",
        colorLight: "#eef2ff",
        correctLevel: QRCode.CorrectLevel.H,
    });
};

const downloadQR = () => {
    if (!state.data) return;

    const canvas = document.querySelector("#instituteQR canvas");
    if (!canvas) {
        showToast(state.data.toast?.qrNotReady || "QR not ready yet");
        return;
    }

    const a = document.createElement("a");
    a.href = canvas.toDataURL("image/png");
    a.download = state.data.shop?.qrFileName || "qr.png";
    a.click();
    showToast(state.data.toast?.qrDownloaded || "QR Code downloaded!");
};

const shareModal = document.getElementById("shareModal");

const openShare = () => {
    shareModal?.classList.add("show");
};

const closeShare = () => {
    shareModal?.classList.remove("show");
};

shareModal?.addEventListener("click", (event) => {
    if (event.target === shareModal) closeShare();
});

const copyToClipboard = (text, successMsg) => {
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(text).then(() => showToast(successMsg));
        return;
    }

    const ta = document.createElement("textarea");
    ta.value = text;
    ta.style.position = "fixed";
    ta.style.opacity = "0";
    document.body.appendChild(ta);
    ta.focus();
    ta.select();
    try {
        document.execCommand("copy");
        showToast(successMsg);
    } catch (error) {
        showToast(`Copy manually: ${text}`);
    }
    document.body.removeChild(ta);
};

const copyLink = () => {
    if (!state.data) return;
    copyToClipboard(
        getWebsite(),
        state.data.toast?.linkCopied || "Link copied!",
    );
    closeShare();
};

const shareWA = () => {
    if (!state.data) return;
    const message = textTemplate(state.data.messages?.shareText, {
        website: getWebsite(),
    });
    window.open(`https://wa.me/?text=${encodeURIComponent(message)}`, "_blank");
    closeShare();
};

const shareFB = () => {
    window.open(
        `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(getWebsite())}`,
        "_blank",
    );
    closeShare();
};

const shareNative = () => {
    if (!state.data) return;

    if (navigator.share) {
        navigator.share({
            title: state.data.shop?.name || "",
            url: getWebsite(),
        });
        closeShare();
    } else {
        copyLink();
    }
};

const showToast = (message) => {
    const toast = document.getElementById("toast");
    if (!toast) return;
    toast.innerHTML = `<i class="bi bi-check2-circle"></i> ${message}`;
    toast.classList.add("show");
    setTimeout(() => toast.classList.remove("show"), 2800);
};

window.addEventListener("load", () => {
    if (!state.data) return;

    const counters = state.data.counters || {};
    animateCounter("cnt-yrs", Number(counters.years || 0), "", 1400);
    animateCounter("cnt-sel", Number(counters.selections || 0), "+", 1800);
    animateCounter("cnt-rate", Number(counters.successRate || 0), "%", 1600);

    const defaultSlot =
        state.data.demo?.slots?.find((slot) => slot.selected) ||
        state.data.demo?.slots?.[0];
    if (defaultSlot?.slot) state.selectedDemoSlot = defaultSlot.slot;

    const tryQR = () => {
        if (typeof QRCode !== "undefined") {
            genQR();
        } else {
            setTimeout(tryQR, 200);
        }
    };
    tryQR();
});
