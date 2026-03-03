const state = {
    data: window.__ACTION_DATA__ || null,
    cart: {},
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

const bindImageFallbacks = () => {
    if (!state.data) return;

    const cover = document.getElementById("coverImage");
    if (cover) {
        cover.addEventListener("error", () => {
            cover.removeAttribute("src");
            cover.style.background = "linear-gradient(135deg,#1a2744,#243560)";
            cover.style.display = "block";
            cover.style.height = "220px";
        });
    }

    const avatar = document.getElementById("avatarImage");
    if (avatar) {
        avatar.addEventListener("error", () => {
            avatar.style.display = "none";
            if (avatar.parentNode) {
                const iconClass = "bi-book-fill";
                avatar.parentNode.innerHTML = `<i class="bi ${iconClass} ico-xl" aria-hidden="true"></i>`;
            }
        });
    }
};

const changeQty = (id, delta) => {
    state.cart[id] = (state.cart[id] || 0) + delta;
    if (state.cart[id] < 0) state.cart[id] = 0;

    const qty = document.getElementById(`qty-${id}`);
    if (qty) qty.textContent = state.cart[id];

    updateCartBadge();
};

const updateCartBadge = () => {
    const total = Object.values(state.cart).reduce((sum, qty) => sum + qty, 0);
    const badge = document.getElementById("cartBadge");
    if (!badge) return;
    badge.textContent = total;
    badge.classList.toggle("show", total > 0);
};

const openCart = () => {
    if (!state.data) return;

    const { cart, products } = state.data;
    const picked = products.filter((p) => state.cart[p.id] > 0);
    const body = document.getElementById("cartBody");
    if (!body) return;

    if (!picked.length) {
        body.innerHTML = `<div class="cart-empty"><i class="bi bi-cart3 ico-lg" aria-hidden="true"></i>${cart.empty}<br>${cart.emptySub}</div>`;
        document.getElementById("cartOverlay")?.classList.add("show");
        return;
    }

    let total = 0;
    let html = picked
        .map((p) => {
            const sub = p.price * state.cart[p.id];
            total += sub;
            return `<div class="cart-item">
        <div class="ci-name">${p.name}<br><small style="color:var(--muted);font-weight:400">₹${p.price} ${cart.each}</small></div>
        <div class="ci-qty">
          <button class="ci-qty-btn" onclick="changeQty(${p.id},-1);openCart()"><i class="bi bi-dash-lg ico-sm" aria-hidden="true"></i></button>
          <span class="ci-qty-num">${state.cart[p.id]}</span>
          <button class="ci-qty-btn" onclick="changeQty(${p.id},1);openCart()"><i class="bi bi-plus-lg ico-sm" aria-hidden="true"></i></button>
        </div>
        <div class="ci-price">₹${sub}</div>
      </div>`;
        })
        .join("");

    html += `<div class="cart-total"><span>${cart.total}</span><span class="cart-total-amt">₹${total}</span></div>`;
    html += `<button class="cart-order-btn" onclick="sendCartWA()"><i class="bi bi-whatsapp ico-sm" aria-hidden="true"></i>${cart.order}</button>`;

    body.innerHTML = html;
    document.getElementById("cartOverlay")?.classList.add("show");
};

const sendCartWA = async () => {
    if (!state.data) return;

    const { products, shop, messages, cart } = state.data;
    const picked = products.filter((p) => state.cart[p.id] > 0);

    let total = 0;
    let message = `${textTemplate(messages.orderHeader, { name: shop.name })}\n\n`;
    const orderItems = [];

    picked.forEach((p) => {
        const sub = p.price * state.cart[p.id];
        total += sub;
        message += `• ${p.name} (${p.author}) x${state.cart[p.id]} = ₹${sub}\n`;
        orderItems.push({
            name: p.name,
            brand: p.author || "",
            qty: state.cart[p.id],
            price: p.price,
            total: sub,
        });
    });

    message += `\n💰 *${cart.total}: ₹${total}*\n\n${messages.orderConfirm}`;

    await sendSubmission("order", {
        source_template: state.data?.meta?.title || "bookshop-template",
        shop_name: shop.name || "",
        name: "",
        phone: "",
        email: "",
        message: "Cart order",
        items: orderItems,
        total: total,
    });

    window.open(
        `https://wa.me/${shop.whatsapp}?text=${encodeURIComponent(message)}`,
        "_blank",
    );
    closeCart();
};

const closeCart = () => {
    document.getElementById("cartOverlay")?.classList.remove("show");
};

const closeCartOutside = (event) => {
    if (event.target === document.getElementById("cartOverlay")) closeCart();
};

const genQR = () => {
    if (!state.data || typeof QRCode === "undefined") return;

    const mount = document.getElementById("vcardQR");
    if (!mount) return;

    mount.innerHTML = "";
    new QRCode(mount, {
        text: getWebsite(),
        width: 165,
        height: 165,
        colorDark: "#1a2744",
        colorLight: "#fdf8f0",
        correctLevel: QRCode.CorrectLevel.H,
    });
};

const saveContact = () => {
    if (!state.data) return;

    const { shop, toast } = state.data;
    const vcard = `BEGIN:VCARD\nVERSION:3.0\nFN:${shop.name}\nORG:${shop.name}\nTEL;TYPE=CELL:${shop.phone}\nEMAIL:${shop.email}\nADR:;;${shop.address};;;;\nURL:${getWebsite()}\nNOTE:${shop.tagline}\nEND:VCARD`;
    const a = document.createElement("a");
    a.href = URL.createObjectURL(new Blob([vcard], { type: "text/vcard" }));
    a.download = shop.vcardFileName;
    a.click();
    showToast(toast.contactSaved);
};

const callShop = () => {
    if (!state.data) return;
    window.location.href = `tel:${state.data.shop.phone}`;
};

const openWA = () => {
    if (!state.data) return;

    const { shop, messages } = state.data;
    window.open(
        `https://wa.me/${shop.whatsapp}?text=${encodeURIComponent(messages.waEnquiry)}`,
        "_blank",
    );
};

const emailShop = () => {
    if (!state.data) return;
    window.location.href = `mailto:${state.data.shop.email}`;
};

const openMaps = () => {
    if (!state.data) return;
    window.open(state.data.shop.maps, "_blank");
};

const downloadQR = () => {
    if (!state.data) return;

    const canvas = document.querySelector("#vcardQR canvas");
    if (!canvas) {
        showToast(state.data.toast.qrNotReady);
        return;
    }

    const a = document.createElement("a");
    a.href = canvas.toDataURL("image/png");
    a.download = state.data.shop.qrFileName;
    a.click();
    showToast(state.data.toast.qrDownloaded);
};

const copyLink = () => {
    if (!state.data) return;

    navigator.clipboard
        .writeText(getWebsite())
        .then(() => showToast(state.data.toast.linkCopied))
        .catch(() => showToast(state.data.toast.linkCopied));

    document.getElementById("shareModal")?.classList.remove("show");
};

const openShare = () => {
    document.getElementById("shareModal")?.classList.add("show");
};

const closeShare = (event) => {
    if (event.target === document.getElementById("shareModal")) {
        document.getElementById("shareModal")?.classList.remove("show");
    }
};

const shareWA = () => {
    if (!state.data) return;

    const { messages, shop } = state.data;
    const message = textTemplate(messages.shareText, {
        name: shop.name,
        website: getWebsite(),
    });

    window.open(`https://wa.me/?text=${encodeURIComponent(message)}`, "_blank");
    document.getElementById("shareModal")?.classList.remove("show");
};

const shareFB = () => {
    if (!state.data) return;

    window.open(
        `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(getWebsite())}`,
        "_blank",
    );
    document.getElementById("shareModal")?.classList.remove("show");
};

const shareNative = () => {
    if (!state.data) return;

    if (navigator.share) {
        navigator.share({
            title: state.data.shop.name,
            url: getWebsite(),
        });
    } else {
        copyLink();
    }
};

const submitContact = async () => {
    if (!state.data) return;

    const name = document.getElementById("cName")?.value.trim();
    const phone = document.getElementById("cPhone")?.value.trim();

    if (!name || !phone) {
        showToast(state.data.toast.namePhoneRequired);
        return;
    }

    const { shop, messages } = state.data;
    const email =
        document.getElementById("cEmail")?.value || messages.fallbackEmail;
    const msg =
        document.getElementById("cMsg")?.value || messages.fallbackMessage;

    const wa = `${textTemplate(messages.contactHeader, { name: shop.name })}\n👤 ${name}\n📞 ${phone}\n📧 ${email}\n💬 ${msg}`;

    await sendSubmission("contact", {
        source_template: state.data?.meta?.title || "bookshop-template",
        shop_name: shop.name || "",
        name,
        phone,
        email,
        message: msg,
    });

    window.open(
        `https://wa.me/${shop.whatsapp}?text=${encodeURIComponent(wa)}`,
        "_blank",
    );

    const contactForm = document.getElementById("contactForm");
    const contactSuccess = document.getElementById("contactSuccess");
    if (contactForm) contactForm.style.display = "none";
    if (contactSuccess) contactSuccess.style.display = "block";
};

const resetContact = () => {
    const contactForm = document.getElementById("contactForm");
    const contactSuccess = document.getElementById("contactSuccess");
    if (contactForm) contactForm.style.display = "block";
    if (contactSuccess) contactSuccess.style.display = "none";

    ["cName", "cPhone", "cEmail", "cMsg"].forEach((id) => {
        const field = document.getElementById(id);
        if (field) field.value = "";
    });
};

const showToast = (message) => {
    const toast = document.getElementById("toast");
    if (!toast) return;

    toast.innerHTML = `<i class="bi bi-check-circle-fill ico-sm" aria-hidden="true"></i>${message}`;
    toast.classList.add("show");
    setTimeout(() => toast.classList.remove("show"), 2500);
};

const initActions = () => {
    bindImageFallbacks();
    updateCartBadge();
    genQR();
};

window.addEventListener("load", initActions);

Object.assign(window, {
    changeQty,
    updateCartBadge,
    openCart,
    sendCartWA,
    closeCart,
    closeCartOutside,
    genQR,
    saveContact,
    callShop,
    openWA,
    emailShop,
    openMaps,
    downloadQR,
    copyLink,
    openShare,
    closeShare,
    shareWA,
    shareFB,
    shareNative,
    submitContact,
    resetContact,
    showToast,
});
