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
        code: null,
        date: null,
        time: null,

        result:
        {
            valid: null,
            message: null,
            studentName: null,
            courseName: null,
            studentPoints: null,
            minPointsRequired: null,
            maxPointsPossible: null,

            certId: null,
            certDatetime: null
        }
    };

    const methods =
    {
        onCodeChange(e) { this.render({ ...this.state, code: e.target.value }); },
        onDateChange(e) { this.render({ ...this.state, date: e.target.value }); },
        onTimeChange(e) { this.render({ ...this.state, time: e.target.value }); },

        onSubmit(e)
        {
            e.preventDefault();

            fetch(EADPI.Helpers.URLGenerator.generateApiUrl('/certificate/auth', { code: this.state.code, datetime: `${this.state.date} ${this.state.time}`} ))
            .then(res => res.json())
            .then(json =>
            {
                if (json.success && json.data)
                    this.render({ ...this.state, result: { valid: true, message: json.success, ...json.data }});
                else if (json.error)
                    this.render({ ...this.state, result: { valid: false, message: json.error }});
            })
            .catch(reason => EADPI.Alerts.push(EADPI.Alerts.types.error, String(reason)));
        }
    };


  const __template = function({ state }) {
    return [  
    h("form", {"class": `max-w-[700px] mx-auto`, "onsubmit": this.onSubmit.bind(this)}, [
      h("ext-label", {"label": `Código`}, [
        h("input", {"type": `number`, "required": ``, "value": state.code, "oninput": this.onCodeChange.bind(this), "min": `1`, "step": `1`}, "")
      ]),
      h("ext-label", {"label": `Data e hora de emissão`}, [
        h("input", {"type": `date`, "required": ``, "value": state.date, "oninput": this.onDateChange.bind(this)}, ""),
        h("input", {"type": `time`, "required": ``, "value": state.time, "step": `1`, "oninput": this.onTimeChange.bind(this)}, "")
      ]),
      h("div", {"class": `text-center`}, [
        h("button", {"type": `submit`, "class": `btn`}, `Verificar`)
      ])
    ]),
    ((state.result.valid) ? h("div", {"class": `max-w-[700px] mx-auto`}, [
      h("p", {"class": `my-4 text-green-700 dark:text-green-300 text-center`}, `${state.result.message}`),
      h("div", {}, [
        h("ext-label", {"label": `Aluno`, "labelbold": `1`}, `${state.result.studentName}`),
        h("ext-label", {"label": `Curso`, "labelbold": `1`}, `${state.result.courseName}`),
        h("ext-label", {"label": `Pontuação`, "labelbold": `1`}, `${state.result.studentPoints} de ${state.result.minPointsRequired} mínimo requerido (máximo de: ${state.result.maxPointsPossible})`),
        h("ext-label", {"label": `Nota`, "labelbold": `1`}, `${Math.floor(state.result.studentPoints / state.result.maxPointsPossible * 100)}%`),
        h("ext-label", {"label": `Código do certificado`, "labelbold": `1`}, `${state.result.certId}`),
        h("ext-label", {"label": `Emissão inicial do certificado`, "labelbold": `1`}, `${state.result.certDatetime}`)
      ])
    ]) : ''),
    ((state.result.valid === false) ? h("div", {"class": `max-w-[700px] mx-auto`}, [
      h("p", {"class": `my-4 text-red-700 dark:text-red-300 text-center`}, `${state.result.message}`)
    ]) : '')
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

  
