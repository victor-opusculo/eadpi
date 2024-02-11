 // Lego version 1.0.0
  import { h, Component } from './lego.min.js'
   
    import { render } from './lego.min.js';
   
    Component.prototype.render = function(state)
    {
      const childs = Array.from(this.childNodes);
      this.__originalChildren = childs.length && !this.__originalChildren?.length ? childs : this.__originalChildren;

       this.__state.slotId = `slot_${performance.now().toString().replace('.','')}_${Math.floor(Math.random() * 1000)}`;
   
      this.setState(state);
      if(!this.__isConnected) return
   
      const rendered = render([
        this.vdom({ state: this.__state }),
        this.vstyle({ state: this.__state }),
      ], this.document);
   
      const slot = this.document.querySelector(`#${this.__state.slotId}`);
      if (slot)
         for (const c of this.__originalChildren)
             slot.appendChild(c);
            
      return rendered;
    };

  
    const state =
    {
        fullname: '',
        email: '',
        password: '',
        password2: '',
        timezone: 'America/Sao_Paulo',
        lgpdConsentCheck: false,
        lgpdtermversion: 0,
        lgpdTermText: '',
        slotId: ''
    };

    const methods =
    {
        nameChanged(e)
        {
            this.render({ ...this.state, fullname: e.target.value });
        },

        emailChanged(e)
        {
            this.render({ ...this.state, email: e.target.value });
        },

        passwordChanged(e)
        {
            this.render({ ...this.state, password: e.target.value });
        },

        password2Changed(e)
        {
            this.render({ ...this.state, password2: e.target.value });
        },

        timezoneChanged(e)
        {
            this.render({ ...this.state, timezone: e.target.value });
        },

        consentChecked(e)
        {
            this.render({ ...this.state, lgpdConsentCheck: e.target.checked });
        },

        showLgpd()
        {
            document.getElementById('lgpdTermDialog')?.showModal();
        },

        submit(e)
        {
            this.render({...this.state, lgpdTermText: document.getElementById('lgpdTermForm')?.elements['lgpdTerm']?.value ?? '***'});
            e.preventDefault();

            if (this.state.password !== this.state.password2)
            {
                EADPI.Alerts.push(EADPI.Alerts.types.info, "As senhas não coincidem!");
                return;
            }

            const data = {};
            for (const prop in this.state)
                data['students:' + prop] = this.state[prop];

            const headers = new Headers({ 'Content-Type': 'application/json' });
            const body = JSON.stringify({ data });
            fetch(EADPI.Helpers.URLGenerator.generateApiUrl('/student/register'), { method: 'POST', headers, body })
            .then(res => res.json())
            .then(json =>
            {
                EADPI.Alerts.pushFromJsonResult(json);
                if (json.success)
                    window.location.href = EADPI.Helpers.URLGenerator.generatePageUrl('/students/login');
            })
            .catch(reason => EADPI.Alerts.push(EADPI.Alerts.types.error, String(reason)));
        }
    };


  const __template = function({ state }) {
    return [  
    h("form", {"class": `mx-auto max-w-[700px]`, "onsubmit": this.submit.bind(this)}, [
      h("ext-label", {"label": `Nome completo`}, [
        h("input", {"type": `text`, "required": ``, "class": `w-full`, "maxlength": `140`, "value": state.fullname, "oninput": this.nameChanged.bind(this)}, "")
      ]),
      h("ext-label", {"label": `E-mail`}, [
        h("input", {"type": `email`, "required": ``, "class": `w-full`, "maxlength": `140`, "value": state.email, "oninput": this.emailChanged.bind(this)}, "")
      ]),
      h("ext-label", {"label": `Senha`}, [
        h("input", {"type": `password`, "required": ``, "class": `w-full`, "maxlength": `140`, "value": state.password, "oninput": this.passwordChanged.bind(this)}, "")
      ]),
      h("ext-label", {"label": `Confirme a senha`}, [
        h("input", {"type": `password`, "required": ``, "class": `w-full`, "maxlength": `140`, "value": state.password2, "oninput": this.password2Changed.bind(this)}, "")
      ]),
      h("ext-label", {"label": `Seu fuso horário`}, [
        h("select", {"onchange": this.timezoneChanged.bind(this)}, [
          ((EADPI.Time.TimeZones).map((dtz) => (h("option", {"value": dtz, "selected": dtz === 'America/Sao_Paulo'}, `${dtz}`))))
        ])
      ]),
      h("div", {"class": `mt-4`}, [
`
            Concorda com o termo de consentimento para uso dos seus dados pessoais?
            `,
        h("button", {"type": `button`, "class": `btn`, "onclick": this.showLgpd.bind(this)}, `Ler`)
      ]),
      h("ext-label", {"reverse": `1`, "label": `Concordo`}, [
        h("input", {"type": `checkbox`, "required": ``, "value": `${state.lgpdTermVersion}`, "checked": state.lgpdConsentCheck, "onchange": this.consentChecked.bind(this)}, "")
      ]),
      h("div", {"class": `text-center mt-4`}, [
        h("button", {"class": `btn`, "type": `submit`}, `Cadastrar-me`)
      ])
    ]),
    h("dialog", {"id": `lgpdTermDialog`, "class": `md:w-[700px] w-screen h-screen backdrop:bg-gray-700/60 p-4 bg-neutral-100 dark:bg-neutral-800`}, [
      h("form", {"id": `lgpdTermForm`, "method": `dialog`}, [
        h("slot", {"id": `${state.slotId}`}, ""),
        h("div", {"class": `text-center my-4`}, [
          h("button", {"class": `btn`, "type": `submit`}, `Fechar`)
        ])
      ])
    ])
  ]
  }

  const __style = function({ state }) {
    return h('style', {}, `
      
      
    `)
  }

  // -- Lego Core
  export default class Lego extends Component {
    init() {
      this.useShadowDOM = false
      if(typeof state === 'object') this.__state = Object.assign({}, state, this.__state)
      if(typeof methods === 'object') Object.keys(methods).forEach(methodName => this[methodName] = methods[methodName])
      if(typeof connected === 'function') this.connected = connected
      if(typeof setup === 'function') setup.bind(this)()
    }
    get vdom() { return __template }
    get vstyle() { return __style }
  }
  // -- End Lego Core

  
