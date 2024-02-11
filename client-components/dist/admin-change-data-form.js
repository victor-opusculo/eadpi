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
        adminid: 0,
        currpassword: '',
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

        currpasswordChanged(e)
        {
            this.render({ ...this.state, currpassword: e.target.value });
        },

        passwordChanged(e)
        {
            this.render({ ...this.state, password: e.target.value });
        },

        password2Changed(e)
        {
            this.render({ ...this.state, password2: e.target.value });
        },

        submit(e)
        {
            e.preventDefault();

            if ((this.state.password || this.state.password2) && (this.state.password !== this.state.password2))
            {
                EADPI.Alerts.push(EADPI.Alerts.types.info, "As senhas não coincidem!");
                return;
            }

            const data = {};
            for (const prop in this.state)
                data['administrators:' + prop] = this.state[prop];

            const headers = new Headers({ 'Content-Type': 'application/json' });
            const body = JSON.stringify({ data });
            fetch(EADPI.Helpers.URLGenerator.generateApiUrl('/administrator/' + this.state.adminid), { method: 'PUT', headers, body })
            .then(res => res.json())
            .then(json =>
            {
                EADPI.Alerts.pushFromJsonResult(json);
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
      h("fieldset", {"class": `fieldset`}, [
        h("legend", {}, `Alterar senha`),
        h("ext-label", {"label": `Senha atual`}, [
          h("input", {"type": `password`, "class": `w-full`, "maxlength": `140`, "value": state.currpassword, "oninput": this.currpasswordChanged.bind(this)}, "")
        ]),
        h("ext-label", {"label": `Nova senha`}, [
          h("input", {"type": `password`, "class": `w-full`, "maxlength": `140`, "value": state.password, "oninput": this.passwordChanged.bind(this)}, "")
        ]),
        h("ext-label", {"label": `Confirme a senha alterada`}, [
          h("input", {"type": `password`, "class": `w-full`, "maxlength": `140`, "value": state.password2, "oninput": this.password2Changed.bind(this)}, "")
        ])
      ]),
      h("div", {"class": `text-center mt-4`}, [
        h("button", {"class": `btn`, "type": `submit`}, `Alterar dados`)
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

  
