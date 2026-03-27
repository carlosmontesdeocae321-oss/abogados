(function () {
  var STORAGE_KEY = "lawfirm_lang";
  var DEFAULT_LANG = "es";
  var currentLang = DEFAULT_LANG;
  var originalTitle = "";

  var textMap = {
    "Inicio": "Home",
    "SERVICIOS": "SERVICES",
    "Servicios": "Services",
    "Casos Ganados": "Successful Cases",
    "ACERCA DE NOSOTROS": "ABOUT US",
    "Acerca de Nosotros": "About Us",
    "Acerca de": "About",
    "Contacto": "Contact",
    "Solicitar cita": "Book appointment",
    "Sobre nosotros": "About us",
    "Navegacion": "Navigation",
    "Navegación": "Navigation",
    "Informacion de contacto": "Contact information",
    "Información de contacto": "Contact information",
    "Horario de atencion": "Business hours",
    "Horario de atención": "Business hours",
    "lunes - Viernes: 9:00 - 17:00": "Monday - Friday: 9:00 - 17:00",
    "Lunes a Viernes: 09:00 - 18:00": "Monday to Friday: 09:00 - 18:00",
    "Lun - Jue: 9:00 - 21:00": "Mon - Thu: 9:00 - 21:00",
    "Vie: 9:00 - 18:00": "Fri: 9:00 - 18:00",
    "Sab: 9:30 - 15:00": "Sat: 9:30 - 15:00",
    "Sáb: 9:30 - 15:00": "Sat: 9:30 - 15:00",
    "Contáctanos": "Contact us",
    "Oficina Principal": "Main Office",
    "Telefono": "Phone",
    "Teléfono": "Phone",
    "Direccion:": "Address:",
    "Dirección:": "Address:",
    "Email": "Email",
    "Solicitar Consulta Legal": "Request Legal Consultation",
    "Enviar solicitud": "Send request",
    "Hablar con un asesor legal": "Talk to a legal advisor",
    "Nombre": "First name",
    "Apellidos": "Last name",
    "Ciudad": "City",
    "Descripcion del caso": "Case description",
    "Descripción del caso": "Case description",
    "Consulta legal gratuita": "Free legal consultation",
    "Consulta rapida": "Quick consultation",
    "Consulta rápida": "Quick consultation",
    "Nombre completo": "Full name",
    "Correo electronico": "Email",
    "Correo electrónico": "Email",
    "Seleccione area de interes": "Select area of interest",
    "Seleccione área de interés": "Select area of interest",
    "Solicitar consulta": "Request consultation",
    "Enviar": "Send",
    "Abrir chat": "Open chat",
    "Cerrar chat": "Close chat",
    "Asistente virtual": "Virtual assistant",
    "El chat se mostrara aqui cuando este listo.": "The chat will appear here when it is ready.",
    "El chat se mostrará aquí cuando esté listo.": "The chat will appear here when it is ready.",
    "Nuestros Servicios": "Our Services",
    "SERVICIOS LEGALES": "LEGAL SERVICES",
    "Mas informacion": "More information",
    "Más información": "More information",
    "Para mayor información,": "For more information,",
    "Ver mas": "See more",
    "Ver más": "See more",
    "Nuestros abogados": "Our lawyers",
    "Ver todo el equipo →": "View full team ->",
    "Lo que dicen nuestros clientes": "What our clients say",
    "Que dicen los clientes": "What clients say",
    "Qué dicen los clientes": "What clients say",
    "Casos Exitosos": "Successful Cases",
    "Años de experiencia": "Years of experience",
    "Abogados Calificados": "Qualified Lawyers",
    "Clientes Confiables": "Trusted Clients",
    "Todos los derechos reservados.": "All rights reserved.",
    "Acerca de nosotros": "About us"
  };

  Object.assign(textMap, {
    "Ayudamos a resolver problemas legales empresariales": "We help solve business legal challenges",
    "¡Tu solución legal comienza aquí!": "Your legal solution starts here!",
    "Su solución legal definitiva": "Your ultimate legal solution",
    "Defienda sus derechos constitucionales con ayuda legal": "Defend your constitutional rights with legal support",
    "¿Necesita servicios legales?": "Need legal services?",
    "Asesoría legal clara y estratégica para empresas y particulares. Nuestro equipo prioriza soluciones rápidas y efectivas: revisamos su caso, le explicamos opciones prácticas y diseñamos una estrategia a medida para proteger sus intereses.": "Clear, strategic legal advice for companies and individuals. Our team prioritizes fast, effective solutions: we review your case, explain practical options, and design a tailored strategy to protect your interests.",
    "Llámanos ahora: +593 99 887 766": "Call us now: +593 99 887 766",
    "Consulta inicial gratuita": "Free initial consultation",
    "Agenda una consulta inicial gratuita y sin compromiso. También puedes escribir a": "Book a free, no-obligation initial consultation. You can also write to",
    "y te responderemos en menos de 24 horas.": "and we will reply in less than 24 hours.",
    "10 años de experiencia ofreciendo servicios legales de alta calidad": "10 years of experience delivering high-quality legal services",
    "Nuestro equipo de abogados ofrece asesoría legal especializada con un enfoque profesional, comprometido y estratégico. Brindamos soluciones jurídicas efectivas para proteger los derechos e intereses de nuestros clientes, acompañándolos en cada etapa de su proceso legal con ética, transparencia y responsabilidad.": "Our legal team provides specialized legal counsel with a professional, committed, and strategic approach. We deliver effective legal solutions to protect our clients' rights and interests, supporting them at every stage of their legal process with ethics, transparency, and responsibility.",
    "Derecho de Adopción 50%": "Adoption Law 50%",
    "Derecho de Familia 80%": "Family Law 80%",
    "Derecho Inmobiliario 70%": "Real Estate Law 70%",
    "Lesiones Personales 40%": "Personal Injury 40%",
    "Brindamos asesoria legal especializada para personas y empresas, con estrategias claras, atencion personalizada y defensa integral en cada etapa del proceso.": "We provide specialized legal counsel for individuals and companies, with clear strategies, personalized attention, and comprehensive defense at every stage of the process.",
    "Brindamos asesoría legal especializada para personas y empresas, con estrategias claras, atención personalizada y defensa integral en cada etapa del proceso.": "We provide specialized legal counsel for individuals and companies, with clear strategies, personalized attention, and comprehensive defense at every stage of the process.",
    "Representacion y asesoria en contratos, obligaciones, conflictos patrimoniales y reclamos para proteger tus derechos e intereses.": "Representation and advice in contracts, obligations, property disputes, and claims to protect your rights and interests.",
    "Representación y asesoría en contratos, obligaciones, conflictos patrimoniales y reclamos para proteger tus derechos e intereses.": "Representation and advice in contracts, obligations, property disputes, and claims to protect your rights and interests.",
    "Defensa legal estrategica en todas las etapas del proceso penal, con acompanamiento cercano y enfoque tecnico.": "Strategic legal defense throughout every stage of criminal proceedings, with close support and technical focus.",
    "Defensa legal estratégica en todas las etapas del proceso penal, con acompañamiento cercano y enfoque técnico.": "Strategic legal defense throughout every stage of criminal proceedings, with close support and technical focus.",
    "Asesoria juridica para empresas en estructuracion, contratos mercantiles, cumplimiento normativo y mitigacion de riesgos.": "Legal advice for companies in structuring, commercial contracts, compliance, and risk mitigation.",
    "Asesoría jurídica para empresas en estructuración, contratos mercantiles, cumplimiento normativo y mitigación de riesgos.": "Legal advice for companies in structuring, commercial contracts, compliance, and risk mitigation.",
    "Gestion legal de compraventas, arrendamientos, regularizacion de bienes y resolucion de disputas inmobiliarias.": "Legal management of purchases, leases, property regularization, and resolution of real estate disputes.",
    "Gestión legal de compraventas, arrendamientos, regularización de bienes y resolución de disputas inmobiliarias.": "Legal management of purchases, leases, property regularization, and resolution of real estate disputes.",
    "Asesoria Corporativa": "Corporate Advisory",
    "Asesoría Corporativa": "Corporate Advisory",
    "Soporte permanente para la toma de decisiones legales en directorios, gobierno corporativo y operaciones clave.": "Ongoing support for legal decision-making in boards, corporate governance, and key operations.",
    "Defensa Legal": "Legal Defense",
    "Patrocinio judicial y extrajudicial con estrategias de litigio y negociacion para alcanzar resultados favorables.": "Judicial and out-of-court representation with litigation and negotiation strategies to achieve favorable outcomes.",
    "Patrocinio judicial y extrajudicial con estrategias de litigio y negociación para alcanzar resultados favorables.": "Judicial and out-of-court representation with litigation and negotiation strategies to achieve favorable outcomes.",
    "10 años de experiencia en diversos casos": "10 years of experience across diverse cases",
    "Ayudamos a las personas a defenderse eficazmente y proteger sus derechos.": "We help people defend themselves effectively and protect their rights.",
    "Testimonios reales de personas y empresas que confiaron en nuestro equipo para defender sus derechos y resolver sus casos con estrategia y compromiso.": "Real testimonials from individuals and companies who trusted our team to defend their rights and resolve their cases with strategy and commitment.",
    "Equipo de abogados con amplia experiencia en derecho civil, laboral, penal, corporativo, etc... Brindamos asesoría estratégica y representación efectiva, siempre con ética profesional y atención personalizada.": "A team of lawyers with broad experience in civil, labor, criminal, corporate law, and more. We provide strategic counsel and effective representation, always with professional ethics and personalized attention.",
    "Consulta rápida": "Quick consultation",
    "Rellena este formulario y te contactaremos a la brevedad.": "Fill out this form and we will contact you shortly.",
    "Describa su caso con libertad": "Describe your case freely",
    "Solicita asesoría legal especializada": "Request specialized legal advice",
    "Completa el formulario y uno de nuestros abogados se comunicará contigo. Tu información será tratada con absoluta confidencialidad profesional.": "Complete the form and one of our lawyers will contact you. Your information will be treated with strict professional confidentiality.",
    "← Volver a Servicios": "<- Back to Services",
    "Solicitud de asesoría": "Advisory request",
    "Autorizo el tratamiento de mis datos para recibir asesoría legal.": "I authorize the processing of my data to receive legal advice.",
    "🔒 Tu información está protegida por confidencialidad abogado-cliente.": "Your information is protected by attorney-client confidentiality.",
    "Contacto rápido": "Quick contact",
    "SERVICIOS LEGALES": "LEGAL SERVICES",
    "Soluciones Legales que Protegen tu Futuro": "Legal Solutions That Protect Your Future",
    "Estrategia, experiencia y resultados: acompañamos a empresas y personas a resolver sus problemas legales con claridad y eficacia.": "Strategy, experience, and results: we support companies and individuals to solve legal issues with clarity and effectiveness.",
    "Conoce nuestras áreas de práctica": "Explore our practice areas",
    "Nuestros servicios están pensados para ofrecer soluciones prácticas y orientadas a resultados. Explora las áreas de práctica a continuación y encuentra la asesoría que mejor se adapta a tu caso.": "Our services are designed to provide practical, results-driven solutions. Explore the practice areas below and find the guidance that best fits your case.",
    "Experiencia 15+ años": "15+ years of experience",
    "Consulta inicial": "Initial consultation",
    "Compraventas, arrendamientos, regularización de títulos y resolución de conflictos inmobiliarios.": "Purchases, leases, title regularization, and resolution of real estate disputes.",
    "Negociación": "Negotiation",
    "Indemnizaciones": "Compensation",
    "Casos complejos": "Complex cases",
    "Reclamaciones, defensa frente a aseguradoras y gestión de siniestros complejos.": "Claims, defense against insurers, and management of complex losses.",
    "Asesoría corporativa": "Corporate advisory",
    "Constitución de sociedades, contratos comerciales y defensa en disputas societarias.": "Company formation, commercial contracts, and defense in corporate disputes.",
    "Peritos": "Experts",
    "Representación en casos de accidentes y daños personales para obtener indemnizaciones justas.": "Representation in accident and personal injury cases to secure fair compensation.",
    "Peritajes clínicos": "Clinical expert reports",
    "Representación": "Representation",
    "Evaluación de expedientes clínicos y coordinación de peritajes en casos de mala praxis.": "Review of clinical records and coordination of expert reports in malpractice cases.",
    "Urgencias 24/7": "24/7 emergencies",
    "Audiencias": "Hearings",
    "Recursos": "Appeals",
    "Defensa estratégica en todas las etapas del proceso penal, protegiendo derechos y libertades.": "Strategic defense at every stage of criminal proceedings, protecting rights and freedoms.",
    "Despidos": "Dismissals",
    "Asesoría en conflictos laborales, despidos, negociaciones y cumplimiento normativo.": "Counsel in labor disputes, dismissals, negotiations, and regulatory compliance.",
    "Planificación": "Planning",
    "Contencioso": "Litigation",
    "Optimización": "Optimization",
    "Planificación fiscal, cumplimiento tributario y representación ante autoridades fiscales.": "Tax planning, tax compliance, and representation before tax authorities.",
    "Marcas & Patentes": "Trademarks & Patents",
    "Licencias": "Licensing",
    "Protección de marcas, patentes, derechos de autor y asesoría en licencias y contratos tecnológicos.": "Protection of trademarks, patents, copyrights, and counsel on licensing and technology contracts.",
    "Contratos": "Contracts",
    "Daños y perjuicios": "Damages",
    "Obligaciones": "Obligations",
    "Asesoría y representación en conflictos civiles, incumplimientos contractuales y reclamos patrimoniales.": "Counsel and representation in civil disputes, contract breaches, and property claims.",
    "Divorcios": "Divorces",
    "Alimentos": "Child support",
    "Custodia": "Custody",
    "Acompañamiento legal en procesos familiares con enfoque humano y protección del interés de niñas y niños.": "Legal support in family proceedings with a human approach and protection of children's best interests.",
    "Compliance Corporativo": "Corporate Compliance",
    "Prevención": "Prevention",
    "Auditoría legal": "Legal audit",
    "Protocolos": "Protocols",
    "Diseño de programas de cumplimiento para reducir riesgos legales, reputacionales y sanciones regulatorias.": "Design of compliance programs to reduce legal and reputational risks and regulatory sanctions.",
    "ACERCA DE NOSOTROS": "ABOUT US",
    "Diseñado con cariño por el equipo de": "Designed with care by the",
    "Nuestro equipo legal": "Our legal team",
    "Excelencia juridica con vision humana": "Legal excellence with a human vision",
    "Excelencia jurídica con visión humana": "Legal excellence with a human vision",
    "Conoce al equipo que respalda cada caso con criterio estrategico, experiencia y compromiso.": "Meet the team that supports every case with strategic judgment, experience, and commitment.",
    "Conoce al equipo que respalda cada caso con criterio estratégico, experiencia y compromiso.": "Meet the team that supports every case with strategic judgment, experience, and commitment.",
    "Cargando abogados...": "Loading lawyers...",
    "Firma de abogados especializada en ofrecer asesoría legal personalizada y defensa eficaz para empresas y particulares.": "Law firm specialized in providing personalized legal advice and effective defense for companies and individuals.",
    "Áreas de Práctica": "Practice Areas",
    "Distribuido por:": "Distributed by:",
    "Contáctanos": "Contact us",
    "Asesoría legal especializada — respuesta rápida y confidencial.": "Specialized legal advice - quick and confidential response.",
    "Nuestro equipo legal está disponible para analizar su caso con absoluta confidencialidad. Contáctenos para recibir asesoría profesional.": "Our legal team is available to review your case with complete confidentiality. Contact us for professional legal advice.",
    "Torres de la Merced": "Torres de la Merced",
    "Víctor Manuel Rendón": "Victor Manuel Rendon",
    "Guayaquil, Ecuador": "Guayaquil, Ecuador",
    "Complete el formulario y uno de nuestros abogados se pondrá en contacto con usted a la brevedad posible.": "Complete the form and one of our lawyers will contact you as soon as possible.",
    "Asunto de la consulta": "Consultation subject",
    "Describa brevemente su caso o consulta legal...": "Briefly describe your case or legal inquiry...",
    "Enviar consulta": "Send inquiry",
    "El equipo mostró un gran profesionalismo en la negociación de un contrato complejo; lograron un acuerdo favorable que protegió nuestros intereses y nos dio tranquilidad para seguir invirtiendo.": "The team showed great professionalism in negotiating a complex contract; they achieved a favorable agreement that protected our interests and gave us peace of mind to keep investing.",
    "Nos asesoraron en un conflicto laboral y consiguieron una solución rápida y justa. Su atención fue cercana y clara en cada paso del proceso.": "They advised us in a labor conflict and secured a fast and fair solution. Their support was close and clear at every step of the process.",
    "La asesoría en cumplimiento regulatorio nos permitió reorganizar procedimientos y evitar sanciones. Su equipo demostró conocimiento técnico y disponibilidad para resolver dudas.": "Their regulatory compliance advice helped us reorganize procedures and avoid sanctions. Their team showed technical expertise and availability to resolve questions.",
    "Derecho Empresarial": "Corporate Law",
    "Derecho Laboral": "Labor Law",
    "Derecho Civil y Regulatorio": "Civil and Regulatory Law",
    "Resultado: Caso ganado": "Result: Case won",
    "Resultado: Victoria": "Result: Victory"
  });

  /* More runtime-discovered translations (second pass) */
  Object.assign(textMap, {
    "El equipo mostró un gran profesionalismo en la negociación de un contrato complejo; lograron un acuerdo favorable que protegió nuestros intereses y nos dio tranquilidad para seguir invirtiendo.": "The team showed great professionalism in negotiating a complex contract; they achieved a favorable agreement that protected our interests and gave us peace of mind to continue investing.",
    "La asesoría en cumplimiento regulatorio nos permitió reorganizar procedimientos y evitar sanciones. Su equipo demostró conocimiento técnico y disponibilidad para resolver dudas.": "Their regulatory compliance advice allowed us to reorganize procedures and avoid sanctions. Their team demonstrated technical knowledge and availability to resolve questions.",
    "Nos asesoraron en un conflicto laboral y consiguieron una solución rápida y justa. Su atención fue cercana y clara en cada paso del proceso.": "They advised us in a labor dispute and obtained a quick and fair solution. Their attention was close and clear at every step of the process.",
    "+": "+",
    "0": "0",
    "10 years of experience across diverse cases": "10 years of experience across diverse cases",
    "10 years of experience delivering high-quality legal services": "10 years of experience delivering high-quality legal services",
    "A team of lawyers with broad experience in civil, labor, criminal, corporate law, and more. We provide strategic counsel and effective representation, always with professional ethics and personalized attention.": "A team of lawyers with broad experience in civil, labor, criminal, and corporate law. We provide strategic counsel and effective representation, always with professional ethics and personalized attention.",
    "Abogados calificados": "Qualified lawyers",
    "About Us": "About Us",
    "About us": "About us",
    "Address:": "Address:",
    "Book appointment": "Book appointment",
    "Business hours": "Business hours",
    "CARTO": "CARTO",
    "Call us now: +593 99 887 766": "Call us now: +593 99 887 766",
    "Casos exitosos": "Successful cases",
    "Civil and Regulatory Law": "Civil and Regulatory Law",
    "Clientes confiables": "Trusted clients",
    "Communications & Image": "Communications & Image",
    "Contact information": "Contact information",
    "Corporate": "Corporate",
    "Corporate Advisory": "Corporate Advisory",
    "Corporate Law": "Corporate Law",
    "Criminal": "Criminal",
    "Debt recovery": "Debt recovery",
    "Defensa Penal": "Criminal Defense",
    "Departments": "Departments",
    "Derecho Administrativo 40%": "Administrative Law 40%",
    "Derecho Civil": "Civil Law",
    "Derecho Constitucional 80%": "Constitutional Law 80%",
    "Derecho Inmobiliario": "Real Estate Law",
    "Derecho Laboral 70%": "Labor Law 70%",
    "Derecho Penal": "Criminal Law",
    "Derecho de Penal 50%": "Criminal Law 50%",
    "EN": "EN",
    "ES": "ES",
    "Estructura especializada para cobertura legal integral y acompañamiento estratégico en áreas clave.": "Specialized structure to provide comprehensive legal coverage and strategic support in key areas.",
    "Fill out this form and we will contact you shortly.": "Fill out this form and we will contact you shortly.",
    "Free legal consultation": "Free legal consultation",
    "Haz clic para activar el mapa": "Click to activate the map",
    "Indicadores Institucionales": "Institutional Indicators",
    "Judicial and out-of-court representation with litigation and negotiation strategies to achieve favorable outcomes.": "Judicial and out-of-court representation with litigation and negotiation strategies to achieve favorable outcomes.",
    "Labor Law": "Labor Law",
    "Lawyer": "Lawyer",
    "Leaflet": "Leaflet",
    "Legal": "Legal",
    "Legal Defense": "Legal Defense",
    "Legal advice for companies in structuring, commercial contracts, compliance, and risk mitigation.": "Legal advice for companies in structuring, commercial contracts, compliance, and risk mitigation.",
    "Legal management of purchases, leases, property regularization, and resolution of real estate disputes.": "Legal management of purchases, leases, title regularization, and resolution of real estate disputes.",
    "Legal-Accounting": "Legal-Accounting",
    "Lesiones Personales": "Personal Injuries",
    "Loading lawyers...": "Loading lawyers...",
    "More information": "More information",
    "Negligencia Médica": "Medical negligence",
    "Nuestra firma en síntesis": "Our firm in brief",
    "Ongoing support for legal decision-making in boards, corporate governance, and key operations.": "Ongoing support for legal decision-making in boards, corporate governance, and key operations.",
    "OpenStreetMap": "OpenStreetMap",
    "Our Services": "Our Services",
    "Our lawyers": "Our lawyers",
    "Our legal team provides specialized legal counsel with a professional, committed, and strategic approach. We deliver effective legal solutions to protect our clients' rights and interests, supporting them at every stage of their legal process with ethics, transparency, and responsibility.": "Our legal team provides specialized legal counsel with a professional, committed, and strategic approach. We deliver effective legal solutions to protect our clients' rights and interests, supporting them at every stage of their legal process with ethics, transparency, and responsibility.",
    "Perfil Institucional": "Institutional Profile",
    "Preferred date": "Preferred date",
    "Preferred time": "Preferred time",
    "Quick consultation": "Quick consultation",
    "Real Estate": "Real Estate",
    "Real testimonials from individuals and companies who trusted our team to defend their rights and resolve their cases with strategy and commitment.": "Real testimonials from individuals and companies who trusted our team to defend their rights and resolve their cases with strategy and commitment.",
    "Representation and advice in contracts, obligations, property disputes, and claims to protect your rights and interests.": "Representation and advice in contracts, obligations, property disputes, and claims to protect your rights and interests.",
    "Request legal consultation": "Request legal consultation",
    "Resultados que respaldan nuestra práctica": "Results that support our practice",
    "Schedule a consultation with one of our lawyers. A team member will contact you to confirm your appointment.": "Schedule a consultation with one of our lawyers. A team member will contact you to confirm your appointment.",
    "See more": "See more",
    "Select area of interest": "Select area of interest",
    "Somos una firma legal multidisciplinaria enfocada en defensa técnica, estrategia jurídica y resultados medibles, con una práctica consolidada desde 2016.": "We are a multidisciplinary law firm focused on technical defense, legal strategy and measurable results, with a practice consolidated since 2016.",
    "Strategic legal defense throughout every stage of criminal proceedings, with close support and technical focus.": "Strategic legal defense throughout every stage of criminal proceedings, with close support and technical focus.",
    "The chat will appear here when it is ready.": "The chat will appear here when it is ready.",
    "Type of consultation": "Type of consultation",
    "View full team ->": "View full team ->",
    "Virtual assistant": "Virtual assistant",
    "We defend your rights with professionalism, experience and commitment. Our firm offers specialized legal advice and strategic representation across various areas of law, delivering clear and effective solutions to protect your interests and those of your company.": "We defend your rights with professionalism, experience and commitment. Our firm offers specialized legal advice and strategic representation across various areas of law, delivering clear and effective solutions to protect your interests and those of your company.",
    "We help people defend themselves effectively and protect their rights.": "We help people defend themselves effectively and protect their rights.",
    "We help solve business legal challenges": "We help solve business legal challenges",
    "We provide specialized legal counsel for individuals and companies, with clear strategies, personalized attention, and comprehensive defense at every stage of the process.": "We provide specialized legal counsel for individuals and companies, with clear strategies, personalized attention, and comprehensive defense at every stage of the process.",
    "What our clients say": "What our clients say",
    "Who we are": "Who we are",
    "Years of experience": "Years of experience",
    "Your legal solution starts here!": "Your legal solution starts here!",
    "Abogados@estudiojimenezyasociados.com": "Abogados@estudiojimenezyasociados.com",
    "© 2026 Alfonso Jimenez & Asociados. All rights reserved.": "© 2026 Alfonso Jimenez & Asociados. All rights reserved."
  });

  /* Additional translations discovered from runtime (missing keys) */
  Object.assign(textMap, {
    "+593 99 887 766": "Call us now: +593 99 887 766",
    "2016": "2016",
    "2017": "2017",
    "2021": "2021",
    "ABOUT US": "ABOUT US",
    "Abogada": "Lawyer",
    "Abogado": "Lawyer",
    "Acompañamiento estratégico en negociación, adquisición y transferencia empresarial.": "Strategic support in negotiation, acquisition and corporate transfers.",
    "Actualidad": "News",
    "Actuamos con integridad, honestidad y respeto por las normas que rigen la práctica del derecho.": "We act with integrity, honesty and respect for the rules governing the practice of law.",
    "Adopción y fortalecimiento de la marca Alfonso Jiménez & Asociados - Firma Legal.": "Adoption and strengthening of the Alfonso Jiménez & Asociados brand - Legal Firm.",
    "Agendar consulta": "Schedule appointment",
    "Agende una consulta con uno de nuestros abogados. Un miembro del equipo se pondrá en contacto para confirmar su cita.": "Schedule a consultation with one of our lawyers. A team member will contact you to confirm your appointment.",
    "Alfonsito": "Alfonsito",
    "Alfonso Jiménez & Asociados": "Alfonso Jiménez & Asociados",
    "Asesoría y representación legal especializada en diversas áreas del derecho.": "Specialized legal advice and representation across various areas of law.",
    "Asumimos cada caso con dedicación y responsabilidad para proteger a nuestros clientes.": "We handle each case with dedication and responsibility to protect our clients.",
    "Brindar asesoría y defensa jurídica integral mediante un equipo multidisciplinario reconocido por su trayectoria, rigor técnico y compromiso ético, protegiendo los derechos y intereses de nuestros clientes.": "Provide comprehensive legal counsel and defense through a multidisciplinary team recognized for its experience, technical rigor and ethical commitment, protecting our clients' rights and interests.",
    "Civil": "Civil",
    "Cobranzas": "Debt recovery",
    "Compra-Venta de Empresas": "Mergers & Acquisitions",
    "Compromiso": "Commitment",
    "Comunicación e Imagen": "Communications & Image",
    "Confidencialidad": "Confidentiality",
    "Conoce nuestra trayectoria, valores y equipo profesional comprometido con la excelencia jurídica.": "Discover our history, values and professional team committed to legal excellence.",
    "Constitución legal de A&JFLI S.A. en Ecuador.": "Legal incorporation of A&JFLI S.A. in Ecuador.",
    "Contact": "Contact",
    "Corporativo": "Corporate",
    "Defendemos tus derechos con profesionalismo, experiencia y compromiso. En nuestro bufete ofrecemos asesoría legal especializada y representación estratégica en distintas áreas del derecho, brindando soluciones claras y efectivas para proteger tus intereses y los de tu empresa.": "We defend your rights with professionalism, experience and commitment. Our firm offers specialized legal advice and strategic representation across various areas of law, delivering clear and effective solutions to protect your interests and those of your company.",
    "Departamentos": "Departments",
    "Ejercemos nuestra labor con seriedad, cumplimiento y respeto por las obligaciones éticas.": "We carry out our work with seriousness, compliance and respect for ethical obligations.",
    "El Socio Fundador, Alfonso Moisés Jiménez Pintado, se graduó como abogado en octubre de 2016 e impulsó la creación del primer estudio jurídico de la firma ese mismo año.": "The Founding Partner, Alfonso Moisés Jiménez Pintado, graduated as a lawyer in October 2016 and launched the firm's first legal office that same year.",
    "En 2017 se integra la Abogada Débora Victoria Mora Mora, actual presidenta. La consolidación del equipo permitió constituir en 2021 a A&JFLI S.A. como empresa legalmente registrada en Ecuador.": "In 2017 Attorney Débora Victoria Mora Mora joined leadership. The consolidation of the team led to the incorporation of A&JFLI S.A. in 2021 as a legally registered company in Ecuador.",
    "Equipo Profesional": "Professional Team",
    "Estructura especializada de la firma": "Specialized firm structure",
    "Estudiante / Pasante": "Student / Intern",
    "Evolución institucional con visión de futuro": "Institutional evolution with a forward-looking vision",
    "Excelencia": "Excellence",
    "Fecha preferida": "Preferred date",
    "Full name": "Full name",
    "Fundación del primer estudio jurídico por parte del socio fundador.": "Foundation of the firm's first legal office by the founding partner.",
    "Gerente del Departamento Jurídico; Gerente del Departamento Financiero, Contable y Tributaria": "Head of Legal Department; Head of Financial, Accounting and Tax Department",
    "Gestión de reputación, presencia institucional y posicionamiento corporativo.": "Reputation management, institutional presence and corporate positioning.",
    "Gestión legal de compra, venta, regularización y trámites de bienes inmuebles.": "Legal management of purchase, sale, regularization and procedures for real estate.",
    "Hitos": "Milestones",
    "Home": "Home",
    "Hora preferida": "Preferred time",
    "Hoy operamos bajo la marca Alfonso Jiménez & Asociados - Firma Legal, fortaleciendo nuestra identidad corporativa y posicionamiento como despacho moderno, confiable y orientado a soluciones legales de alto nivel.": "Today we operate under the brand Alfonso Jiménez & Asociados - Legal Firm, strengthening our corporate identity and positioning as a modern, reliable firm focused on high-level legal solutions.",
    "Inmobiliario": "Real Estate",
    "Jurídico": "Legal",
    "Jurídico-Contable": "Legal-Accounting",
    "Línea de tiempo": "Timeline",
    "Mantenemos altos estándares de calidad mediante preparación técnica y mejora continua.": "We maintain high quality standards through technical preparation and continuous improvement.",
    "Misión": "Mission",
    "Navigation": "Navigation",
    "Next": "Next",
    "Nuestra Historia": "Our History",
    "Otro": "Other",
    "Penal": "Criminal",
    "Perfiles cargados dinámicamente desde la base de datos.": "Profiles loaded dynamically from the database.",
    "Phone": "Phone",
    "Previous": "Previous",
    "Principios que guían nuestra práctica": "Principles that guide our practice",
    "Quiénes Somos": "Who we are",
    "Recuperación de cartera vencida con estrategias legales y extrajudiciales eficientes.": "Recovery of overdue portfolios with efficient legal and extrajudicial strategies.",
    "Resguardamos con absoluta reserva toda la información legal confiada a la firma.": "We safeguard with absolute confidentiality all legal information entrusted to the firm.",
    "Responsabilidad": "Responsibility",
    "Responsable del Departamento de Comunicación Social e Imagen Corporativa": "Head of the Social Communication and Corporate Image Department",
    "SERVICES": "SERVICES",
    "Send": "Send",
    "Ser una firma legal líder y referente a nivel nacional, con proyección internacional, reconocida por la excelencia de su equipo profesional y la solidez de sus estrategias jurídicas.": "To be a leading law firm and national benchmark with international projection, recognized for the excellence of its professional team and the solidity of its legal strategies.",
    "Services": "Services",
    "Solicitar consulta legal": "Request legal consultation",
    "Somos una firma jurídica con enfoque corporativo y visión estratégica, integrada por profesionales especializados en distintas áreas del derecho. Brindamos acompañamiento legal integral, ética profesional y soluciones orientadas a resultados para personas y empresas.": "We are a law firm with a corporate focus and strategic vision, made up of professionals specialized in different areas of law. We provide comprehensive legal support, professional ethics and results-oriented solutions for individuals and companies.",
    "Soporte contable, tributario y financiero con enfoque de cumplimiento normativo.": "Accounting, tax and financial support with a compliance-oriented approach.",
    "Tipo de consulta": "Type of consultation",
    "Valores Institucionales": "Institutional Values",
    "Visión": "Vision",
    "© 2026 Alfonso Jimenez & Asociados. Todos los derechos reservados.": "© 2026 Alfonso Jimenez & Asociados. All rights reserved."
  });

    /* Runtime: cover remaining exact missing keys observed in console */
    Object.assign(textMap, {
      "A team of lawyers with broad experience in civil, labor, criminal, and corporate law. We provide strategic counsel and effective representation, always with professional ethics and personalized attention.": "A team of lawyers with broad experience in civil, labor, criminal, and corporate law. We provide strategic counsel and effective representation, always with professional ethics and personalized attention.",
      "Administrative Law 40%": "Administrative Law 40%",
      "Civil Law": "Civil Law",
      "Constitutional Law 80%": "Constitutional Law 80%",
      "Criminal Defense": "Criminal Defense",
      "Criminal Law": "Criminal Law",
      "Criminal Law 50%": "Criminal Law 50%",
      "Institutional Indicators": "Institutional Indicators",
      "Institutional Profile": "Institutional Profile",
      "Labor Law 70%": "Labor Law 70%",
      "Legal management of purchases, leases, title regularization, and resolution of real estate disputes.": "Legal management of purchases, leases, title regularization, and resolution of real estate disputes.",
      "Medical negligence": "Medical negligence",
      "Mensaje (opcional)": "Message (optional)",
      "Mergers & Acquisitions": "Mergers & Acquisitions",
      "Monday to Friday: 09:00 - 18:00": "Monday to Friday: 09:00 - 18:00",
      "Other": "Other",
      "Our firm in brief": "Our firm in brief",
      "Personal Injuries": "Personal Injuries",
      "Qualified lawyers": "Qualified lawyers",
      "Real Estate Law": "Real Estate Law",
      "Results that support our practice": "Results that support our practice",
      "Schedule appointment": "Schedule appointment",
      "Specialized structure to provide comprehensive legal coverage and strategic support in key areas.": "Specialized structure to provide comprehensive legal coverage and strategic support in key areas.",
      "Successful cases": "Successful cases",
      "Trusted clients": "Trusted clients",
      "We are a multidisciplinary law firm focused on technical defense, legal strategy and measurable results, with a practice consolidated since 2016.": "We are a multidisciplinary law firm focused on technical defense, legal strategy and measurable results, with a practice consolidated since 2016.",
      "Torres de la Merced, Victor Manuel Rendón, Guayaquil, piso 20": "Torres de la Merced, Victor Manuel Rendón, Guayaquil, floor 20",
      "Torres de la Merced, Víctor Manuel Rendón, Guayaquil, Ecuador": "Torres de la Merced, Víctor Manuel Rendón, Guayaquil, Ecuador",
      "contributors ©": "contributors ©",
      "|": "|",
      "©": "©",
      "×": "×",
      "−": "-",
      "★": "★",
      "Click to activate the map": "Click to activate the map",
      "Socio Fundador": "Founding Partner",
      "Alfonso Moisés Jiménez Pintado": "Alfonso Moises Jimenez Pintado",
      "Rodolfo Rolando Saldarriaga Solórzano": "Rodolfo Rolando Saldarriaga Solórzano",
      "Raquel Iraida Jiménez Pintado": "Raquel Iraida Jimenez Pintado",
      "María García": "Maria Garcia",
      "Carlos Méndez": "Carlos Mendez",
      "Luisa Paredes": "Luisa Paredes",
      "El equipo mostró un gran profesionalismo en la negociación de un contrato complejo; lograron un acuerdo favorable que protegió nuestros intereses y nos dio tranquilidad para seguir invirtiendo.": "The team showed great professionalism in negotiating a complex contract; they achieved a favorable agreement that protected our interests and gave us peace of mind to continue investing.",
      "La asesoría en cumplimiento regulatorio nos permitió reorganizar procedimientos y evitar sanciones. Su equipo demostró conocimiento técnico y disponibilidad para resolver dudas.": "Their regulatory compliance advice allowed us to reorganize procedures and avoid sanctions. Their team demonstrated technical knowledge and availability to resolve questions.",
      "Nos asesoraron en un conflicto laboral y consiguieron una solución rápida y justa. Su atención fue cercana y clara en cada paso del proceso.": "They advised us in a labor dispute and obtained a quick and fair solution. Their attention was close and clear at every step of the process."
    });

  var titleMap = {
    "Alfonso Jimenez & Asociados": "Alfonso Jimenez & Asociados",
    "Solicitar asesoría — Alfonso Jimenez & Asociados": "Request legal advice - Alfonso Jimenez & Asociados"
  };

  /* Practice page: additional runtime-discovered translations */
  Object.assign(textMap, {
    "Acción de hábeas corpus.": "Habeas corpus action.",
    "Acción de hábeas data.": "Habeas data action.",
    "Acción de protección.": "Protection action.",
    "Acción extraordinaria de protección.": "Extraordinary protection action.",
    "Acción por incumplimiento.": "Action for breach.",
    "Análisis jurídico de operaciones económicas.": "Legal analysis of economic operations.",
    "Asesoría en contratación pública.": "Public procurement advice.",
    "Asesoría en contratos civiles y comerciales.": "Advice on civil and commercial contracts.",
    "Asesoría en terminación de relaciones laborales.": "Advice on termination of employment relations.",
    "Asesoría jurídica para personas y empresas en actividades económicas y comerciales, orientada a seguridad jurídica y eficiencia operativa.": "Legal advice for individuals and companies in economic and commercial activities, focused on legal certainty and operational efficiency.",
    "Asesoría preventiva para empresas y emprendimientos.": "Preventive advice for companies and startups.",
    "Asesoría preventiva para empresas.": "Preventive advice for companies.",
    "Asesoría y patrocinio en relaciones familiares, con enfoque de protección integral para niñas, niños, adolescentes y familias.": "Advice and representation in family relations, with an integral protection approach for children, adolescents and families.",
    "Asesoría, patrocinio y defensa en procesos penales en todas sus etapas, garantizando el debido proceso y la protección de derechos constitucionales.": "Advice, representation and defense in criminal proceedings at all stages, guaranteeing due process and protection of constitutional rights.",
    "Asistencia jurídica a víctimas de delitos.": "Legal assistance to crime victims.",
    "Consultar caso": "Consult case",
    "Defensa en procesos contencioso administrativos.": "Defense in administrative litigation proceedings.",
    "Defensa jurídica de derechos y garantías constitucionales frente a vulneraciones por autoridades públicas o particulares.": "Legal defense of constitutional rights and guarantees against violations by public authorities or private parties.",
    "Defensa técnica en procesos penales.": "Technical defense in criminal proceedings.",
    "Derecho Administrativo": "Administrative Law",
    "Derecho Constitucional": "Constitutional Law",
    "Derecho Económico": "Economic Law",
    "Derecho de Familia": "Family Law",
    "Explore our practice areas": "Explore our practice areas",
    "Impugnación de actos administrativos.": "Challenge of administrative acts.",
    "Impugnación de medidas cautelares.": "Challenge of precautionary measures.",
    "LEGAL SERVICES": "LEGAL SERVICES",
    "Legal Solutions That Protect Your Future": "Legal Solutions That Protect Your Future",
    "Liquidaciones laborales e indemnizaciones.": "Severance payments and compensation.",
    "Message (optional)": "Message (optional)",
    "Our services are designed to provide practical, results-driven solutions. Explore the practice areas below and find the guidance that best fits your case.": "Our services are designed to provide practical, results-driven solutions. Explore the practice areas below and find the guidance that best fits your case.",
    "Patrocinio en audiencias y juicios penales.": "Representation in criminal hearings and trials.",
    "Patrocinio en juicios laborales.": "Representation in labor trials.",
    "Patrocinio y asesoría legal para trabajadores y empleadores en conflictos laborales y cumplimiento de normativa vigente en Ecuador.": "Representation and legal advice for workers and employers in labor disputes and compliance with current regulations in Ecuador.",
    "Pensiones alimenticias.": "Child support payments.",
    "Procedimientos ante entidades públicas.": "Procedures before public entities.",
    "Procesos de divorcio.": "Divorce proceedings.",
    "Reclamaciones por despidos intempestivos.": "Claims for unfair dismissals.",
    "Recursos administrativos.": "Administrative remedies.",
    "Recursos y acciones dentro del proceso penal.": "Remedies and actions within criminal proceedings.",
    "Representación de personas y empresas en procedimientos frente a instituciones del Estado y defensa ante actos administrativos.": "Representation of individuals and companies in proceedings before State institutions and defense against administrative acts.",
    "Representación en conflictos comerciales.": "Representation in commercial disputes.",
    "Resolución de controversias económicas.": "Resolution of economic disputes.",
    "Régimen de visitas.": "Visitation regime.",
    "Tenencia de menores.": "Custody of minors.",
    "Violencia intrafamiliar y reconocimiento de paternidad.": "Domestic violence and paternity recognition."
  });

  /* Mapeo completo de navbar y secciones faltantes */
  Object.assign(textMap, {
    "Consulta Judiciales": "Judicial Consultation",
    "Consulta judiciales": "Judicial Consultation",
    "Consultas Judiciales": "Judicial Consultations",
    "Educacion Continua": "Continuous Education",
    "Educación Continua": "Continuous Education",
    "Perfiles cargados dinámicamente desde la base de datos.": "Profiles loaded dynamically from the database.",
    "Quiénes Somos": "Who We Are",
    "Quienes Somos": "Who We Are",
    "Nuestra Historia": "Our History",
    "Nuestra historia": "Our history",
    "Evolución institucional con visión de futuro": "Institutional evolution with forward vision",
    "Línea de tiempo": "Timeline",
    "Hitos": "Milestones",
    "Principios que guían nuestra práctica": "Principles that guide our practice",
    "Valores Institucionales": "Institutional Values",
    "Estructura especializada de la firma": "Specialized firm structure",
    "No hay abogados cargados aún. Puedes agregarlos desde el panel de administración.": "No lawyers loaded yet. You can add them from the administration panel.",
    "Socio Fundador": "Founding Partner",
    "Máster": "Master's degree",
    "Abogado": "Lawyer",
    "Candidato a Doctor (PhD)": "PhD Candidate",
    "Universidad Estatal de Guayaquil": "Guayaquil State University",
    "Universidad Católica Andrés Bello": "Andrés Bello Catholic University",
    "Derecho Procesal Constitucional": "Constitutional Procedural Law",
    "Derecho Procesal Penal": "Criminal Procedural Law",
    "Universidad Estatal de Milagro (UNEMI)": "Milagro State University (UNEMI)",
    "Política Criminal y Derecho Penitenciario": "Criminal Policy and Penitentiary Law",
    "Criminología, Victimología y Delincuencia": "Criminology, Victimology and Delinquency",
    "Universidad Internacional de Valencia": "International University of Valencia",
    "Ver todo el equipo": "View full team",
    "Lo que dicen nuestros clientes": "What our clients say",
    "Testimonios reales de personas y empresas que confiaron en nuestro equipo para defender sus derechos y resolver sus casos con estrategia y compromiso.": "Real testimonials from individuals and companies who trusted our team to defend their rights and resolve their cases with strategy and commitment.",
    "Equipo Profesional": "Professional Team",
    "Acerca de nosotros": "About us",
    "Nuestro equipo legal": "Our legal team",
    "Integración de la Abgda. Débora Victoria Mora Mora al liderazgo institucional.": "Integration of Attorney Débora Victoria Mora Mora into institutional leadership.",
    "Integración de la Abg. Débora Victoria Mora Mora al liderazgo institucional.": "Integration of Attorney Débora Victoria Mora Mora into institutional leadership.",
    "Consultar caso": "Consult case",
    "Ingresar": "Login",
    "Registrarse": "Register",
    "Cargando...": "Loading...",
    "Ocurrió un error. Por favor intenta de nuevo.": "An error occurred. Please try again.",
    "Error": "Error",
    "Éxito": "Success",
    "Enviando...": "Sending....",
    "Volver": "Back",
    "Atrás": "Back",
    "Aceptar": "Accept",
    "Cancelar": "Cancel",
    "Guardar": "Save",
    "Actualizar": "Update",
    "Eliminar": "Delete",
    "Sí": "Yes",
    "No": "No",
    /* Traducciones de Educación Continua - EGLO */
    "Formación jurídica continua orientada a la práctica profesional y al fortalecimiento del ejercicio del derecho.": "Continuous legal training focused on professional practice and strengthening the exercise of law.",
    "Una iniciativa de litigantes comprometidos con la excelencia jurídica": "An initiative of litigants committed to legal excellence",
    "EGLO nace en el año 2025, desde la visión y experiencia de las Abogadas Ericka Bonilla Salazar y Sandra Pincay García, profesionales del derecho que integran la firma legal Jiménez y Asociados. Jóvenes abogadas que al litigar se enfrentaron rápidamente a una realidad clara: la de muchos jóvenes abogados que carecían de la preparación necesaria para litigar con confianza y eficiencia en el sistema judicial ecuatoriano.": "EGLO was born in 2025, from the vision and experience of Attorneys Ericka Bonilla Salazar and Sandra Pincay García, legal professionals who are part of the Jiménez and Associates law firm. Young attorneys who quickly faced a clear reality when litigating: that of many young lawyers who lacked the necessary preparation to litigate with confidence and efficiency in the Ecuadorian judicial system.",
    "Motivadas por la necesidad de un centro de formación de litigación oral enfocado en la práctica, se crea la Escuela de Litigación Oral Ecuatoriana \"EGLO\", en conjunto y bajo el liderazgo del Abogado Alfonso Jiménez Pintado, con el objetivo claro de proporcionar una formación completa, rigurosa y accesible a través de modalidades virtuales como presenciales.": "Motivated by the need for an oral litigation training center focused on practice, the Ecuadorian School of Oral Litigation \"EGLO\" was created, jointly and under the leadership of Attorney Alfonso Jiménez Pintado, with the clear objective of providing complete, rigorous and accessible training through virtual and face-to-face modalities.",
    "Misión y Visión": "Mission and Vision",
    "Misión": "Mission",
    "EGLO busca ser una Escuela líder en formación y capacitación jurídica a nivel nacional, enfocada en crear la nueva generación de abogados litigantes en Ecuador. Proporcionamos una educación jurídica integral que abarca desde la teoría hasta la práctica en juicios orales, comprometiéndonos a impulsar el desarrollo de profesionales prolijos, meticulosos, competentes y altamente capacitados para sobresalir en el sistema de justicia actual.": "EGLO seeks to be a leading school in legal training and education at the national level, focused on creating the new generation of trial lawyers in Ecuador. We provide comprehensive legal education that ranges from theory to practice in oral trials, committing ourselves to promote the development of thorough, meticulous, competent and highly trained professionals to excel in the current justice system.",
    "Visión": "Vision",
    "Aspiramos a ser reconocidos como la institución líder en formación y capacitación jurídica, promoviendo una generación de abogados litigantes innovadores, preparados para enfrentar los desafíos del sistema judicial moderno. Nos comprometemos con la excelencia, la ética profesional y el compromiso con la justicia a través de una educación práctica y transformadora en el litigio oral.": "We aspire to be recognized as the leading institution in legal training and education, promoting a generation of innovative litigation lawyers, prepared to face the challenges of the modern judicial system. We are committed to excellence, professional ethics and commitment to justice through practical and transformative education in oral litigation.",
    "Modalidades de Formación": "Training Modalities",
    "Presencial": "Face-to-face",
    "Sesiones en vivo con interacción directa con instructores, abogados litigantes y compañeros de estudio en aula.": "Live sessions with direct interaction with instructors, trial lawyers and study partners in the classroom.",
    "Virtual": "Online",
    "Acceso desde cualquier lugar con total flexibilidad de horarios. Aprende a tu ritmo con acceso 24/7.": "Access from anywhere with complete schedule flexibility. Learn at your own pace with 24/7 access.",
    "Modalidad Mixta": "Hybrid Model",
    "Combinación estratégica de sesiones presenciales y virtuales para máxima flexibilidad y eficiencia.": "Strategic combination of face-to-face and virtual sessions for maximum flexibility and efficiency.",
    "Eventos y Programas": "Events and Programs",
    "Ofrecemos Cursos, Diplomados, Seminarios, Talleres y Conversatorios especializados en distintas ramas del Derecho": "We offer Courses, Diplomas, Seminars, Workshops and Talks specialized in different branches of Law",
    "Derecho Penal": "Criminal Law",
    "Derecho Civil": "Civil Law",
    "Derecho de Familia": "Family Law",
    "Derecho Constitucional": "Constitutional Law",
    "Derecho Societario": "Corporate Law",
    "Derecho Laboral": "Labor Law",
    "Derecho Administrativo": "Administrative Law",
    "Instituciones y Aliados Académicos": "Institutions and Academic Partners",
    "Aliado académico 2": "Academic partner 2",
    /* Traducciones de Servicios Judiciales */
    "Consulta tus Procesos Judiciales": "Consult Your Judicial Processes",
    "Accede a enlaces oficiales del sistema judicial ecuatoriano: consultas de procesos, certificados y servicios institucionales.": "Access official links from the Ecuadorian judicial system: process inquiries, certificates and institutional services.",
    "Accesos directos": "Direct access",
    "Consulta de procesos": "Process consultation",
    "Verifica causas judiciales en el sistema oficial.": "Verify judicial cases in the official system.",
    "Noticias del delito": "Crime news",
    "Consulta reportes y registros de denuncias.": "Consult reports and complaint records.",
    "Pensiones alimenticias": "Child support",
    "Accede al sistema SUPA de la Función Judicial.": "Access the SUPA system of the Judicial Function.",
    "Antecedentes penales": "Criminal record",
    "Obtén tu certificado oficial en línea.": "Get your official certificate online."
  });

  var attrMap = {
    placeholder: {
      "Escribe tu mensaje...": "Type your message...",
      "Nombre": "First name",
      "Apellidos": "Last name",
      "Telefono": "Phone",
      "Teléfono": "Phone",
      "Descripcion": "Description",
      "Ej: Juan Perez": "Ex: John Smith",
      "Ej: Juan Pérez": "Ex: John Smith",
      "nombre@dominio.com": "name@domain.com",
      "Describa su caso con libertad": "Describe your case freely",
      "Correo electrónico": "Email",
      "Asunto de la consulta": "Consultation subject",
      "Describa brevemente su caso o consulta legal...": "Briefly describe your case or legal inquiry..."
    },
    "aria-label": {
      "Mensaje": "Message",
      "Enviar": "Send",
      "Abrir chat": "Open chat",
      "Cerrar chat": "Close chat",
      "Idioma": "Language"
    },
    value: {
      "Solicitar consulta": "Request consultation",
      "Enviar": "Send"
    }
  };

  var alertMap = {
    "Solicitud enviada. Gracias, nos pondremos en contacto.": "Request sent. Thank you, we will contact you soon.",
    "Ocurrió un error al enviar la solicitud. Intente de nuevo.": "An error occurred while sending your request. Please try again.",
    "Solicitud enviada.": "Request sent.",
    "Solicitud enviada. Nos comunicaremos contigo pronto.": "Request sent. We will contact you soon.",
    "Error al enviar. Intenta más tarde.": "Error sending request. Please try again later.",
    "Error de red. Intenta de nuevo.": "Network error. Please try again."
  };

  var originalText = new WeakMap();
  var textNodes = [];
  var attributeStore = [];
  var bootstrapped = false;

  function normalize(str) {
    return (str || "").replace(/\s+/g, " ").trim();
  }

  function toEnglish(str) {
    var key = normalize(str);
    if (textMap[key]) return textMap[key];
    // record missing keys for easier mapping/debugging
    try {
      window.__i18n_missing = window.__i18n_missing || {};
      if (!window.__i18n_missing[key]) {
        window.__i18n_missing[key] = 0;
      }
      window.__i18n_missing[key]++;
    } catch (e) {}
    return str;
  }

  function translateKeepingWhitespace(original, translatedCore) {
    var match = original.match(/^(\s*)([\s\S]*?)(\s*)$/);
    if (!match) return translatedCore;
    return match[1] + translatedCore + match[3];
  }

  function collectTextNodes() {
    // reset stored nodes to capture current DOM state (useful when run after dynamic inserts)
    textNodes = [];
    originalText = new WeakMap();

    var walker = document.createTreeWalker(document.body, NodeFilter.SHOW_TEXT, {
      acceptNode: function (node) {
        if (!node || !node.nodeValue) return NodeFilter.FILTER_REJECT;
        if (!normalize(node.nodeValue)) return NodeFilter.FILTER_REJECT;
        var parent = node.parentElement;
        if (!parent) return NodeFilter.FILTER_REJECT;
        var tag = parent.tagName;
        if (tag === "SCRIPT" || tag === "STYLE" || tag === "NOSCRIPT") return NodeFilter.FILTER_REJECT;
        return NodeFilter.FILTER_ACCEPT;
      }
    });

    while (walker.nextNode()) {
      var n = walker.currentNode;
      textNodes.push(n);
      originalText.set(n, n.nodeValue);
    }
  }

  function collectAttributes() {
    // rebuild attribute store to reflect current DOM
    attributeStore = [];
    var attrs = Object.keys(attrMap);
    for (var i = 0; i < attrs.length; i++) {
      var attr = attrs[i];
      var elements = document.querySelectorAll("[" + attr + "]");
      for (var j = 0; j < elements.length; j++) {
        attributeStore.push({ el: elements[j], attr: attr, original: elements[j].getAttribute(attr) });
      }
    }
  }

  function translateTextNodeIfNeeded(node) {
    if (!node || node.nodeType !== Node.TEXT_NODE) return;
    if (currentLang !== "en") return;
    var current = node.nodeValue || "";
    var core = normalize(current);
    if (!core) return;
    var translated = toEnglish(core);
    if (translated !== core) {
      node.nodeValue = translateKeepingWhitespace(current, translated);
    }
  }

  function translateElementTree(element) {
    if (!element) return;
    if (element.nodeType === Node.TEXT_NODE) {
      translateTextNodeIfNeeded(element);
      return;
    }
    if (element.nodeType !== Node.ELEMENT_NODE) return;
    var walker = document.createTreeWalker(element, NodeFilter.SHOW_TEXT, null);
    while (walker.nextNode()) {
      translateTextNodeIfNeeded(walker.currentNode);
    }
  }

  function observeDynamicText() {
    var observer = new MutationObserver(function (mutations) {
      if (currentLang !== "en") return;
      for (var i = 0; i < mutations.length; i++) {
        var m = mutations[i];
        if (m.type === "characterData") {
          translateTextNodeIfNeeded(m.target);
          continue;
        }
        if (m.type === "childList") {
          for (var j = 0; j < m.addedNodes.length; j++) {
            translateElementTree(m.addedNodes[j]);
          }
        }
      }
    });

    observer.observe(document.body, {
      childList: true,
      subtree: true,
      characterData: true
    });
  }

  function translateServiceLine(lang) {
    var el = document.getElementById("serviceSelected");
    if (!el) return;
    var txt = el.textContent || "";
    var serviceName = txt;

    if (/^Servicio:\s*/i.test(txt)) {
      serviceName = txt.replace(/^Servicio:\s*/i, "");
    } else if (/^Service:\s*/i.test(txt)) {
      serviceName = txt.replace(/^Service:\s*/i, "");
    }

    el.textContent = (lang === "en" ? "Service: " : "Servicio: ") + serviceName;
  }

  function applyLanguage(lang) {
    var useEnglish = lang === "en";
    currentLang = lang;
    document.documentElement.lang = lang;

    // Recollect text nodes and attributes to ensure we translate the full current DOM
    collectTextNodes();
    collectAttributes();

    for (var i = 0; i < textNodes.length; i++) {
      var node = textNodes[i];
      var original = originalText.get(node);
      if (typeof original !== "string") continue;
      var rawCore = normalize(original);
      if (!rawCore) continue;
      var target = useEnglish ? toEnglish(rawCore) : rawCore;
      node.nodeValue = translateKeepingWhitespace(original, target);
    }

    for (var j = 0; j < attributeStore.length; j++) {
      var item = attributeStore[j];
      var currentOriginal = item.original || "";
      var map = attrMap[item.attr] || {};
      var key = normalize(currentOriginal);
      var translated = map[key] || currentOriginal;
      item.el.setAttribute(item.attr, useEnglish ? translated : currentOriginal);
    }

    translateServiceLine(lang);
    if (originalTitle) {
      var titleKey = normalize(originalTitle);
      var translatedTitle = titleMap[titleKey] || originalTitle;
      document.title = useEnglish ? translatedTitle : originalTitle;
    }
    updateLanguageBadge(lang);
    localStorage.setItem(STORAGE_KEY, lang);
  }

  function updateLanguageBadge(lang) {
    var badge = document.getElementById("lang-current");
    if (badge) badge.textContent = (lang || "es").toUpperCase();
  }

  function injectLanguageStyles() {
    if (document.getElementById("i18n-lang-style")) return;
    var style = document.createElement("style");
    style.id = "i18n-lang-style";
    style.textContent = ""
      + ".lang-switcher > a{font-weight:700;}"
      + ".lang-switcher .dropdown{min-width:70px;text-align:left;}"
      + ".lang-switcher .dropdown li a{padding:8px 16px;}";
    document.head.appendChild(style);
  }

  function injectLanguageSelector() {
    var menu = document.querySelector(".menu-1 ul");
    if (!menu || document.getElementById("lang-switcher")) {
      // if menu not found yet, observe for it and inject when available
      if (!menu) {
        var mo = new MutationObserver(function (mutations, obs) {
          var m = document.querySelector(".menu-1 ul");
          if (m) {
            obs.disconnect();
            injectLanguageSelector();
          }
        });
        mo.observe(document.body, { childList: true, subtree: true });
      }
      return;
    }

    var li = document.createElement("li");
    li.id = "lang-switcher";
    li.className = "has-dropdown lang-switcher";
    li.innerHTML = ""
      + '<a href="#" id="lang-current" aria-label="Idioma">ES</a>'
      + '<ul class="dropdown">'
      + '<li><a href="#" data-set-lang="es">ES</a></li>'
      + '<li><a href="#" data-set-lang="en">EN</a></li>'
      + "</ul>";

    menu.appendChild(li);

    li.addEventListener("click", function (ev) {
      var target = ev.target || ev.srcElement;
      var trigger = target && target.getAttribute ? target.getAttribute("data-set-lang") : "";
      // If clicked on one of the dropdown language options, set language and reload so the whole page updates
      if (trigger) {
        ev.preventDefault();
        try { localStorage.setItem(STORAGE_KEY, trigger); } catch (e) {}
        // short delay to ensure storage is committed
        setTimeout(function(){ location.reload(); }, 40);
        return;
      }
      // If clicked the visible badge/current language, toggle between 'es' and 'en' and reload
      if (target && target.id === 'lang-current') {
        ev.preventDefault();
        var flip = (currentLang === 'en') ? 'es' : 'en';
        try { localStorage.setItem(STORAGE_KEY, flip); } catch (e) {}
        setTimeout(function(){ location.reload(); }, 40);
        return;
      }
    });
  }

  function patchAlert() {
    if (window.__lawfirmAlertPatched) return;
    window.__lawfirmAlertPatched = true;

    var originalAlert = window.alert;
    window.alert = function (message) {
      try {
        var lang = localStorage.getItem(STORAGE_KEY) || DEFAULT_LANG;
        if (lang === "en" && typeof message === "string" && alertMap[message]) {
          return originalAlert.call(window, alertMap[message]);
        }
      } catch (e) {
      }
      return originalAlert.call(window, message);
    };
  }

  function bootstrap() {
    if (bootstrapped) return;
    bootstrapped = true;

    injectLanguageStyles();
    injectLanguageSelector();
    collectTextNodes();
    collectAttributes();
    observeDynamicText();
    patchAlert();
    originalTitle = document.title || "";

    var stored = localStorage.getItem(STORAGE_KEY);
    var lang = stored === "en" ? "en" : DEFAULT_LANG;
    applyLanguage(lang);

    window.setSiteLanguage = applyLanguage;
    
    // Expose translation function for chatbot
    window.translateChatbotText = function(spanishText) {
      if (currentLang !== "en") return spanishText;
      var key = normalize(spanishText);
      return textMap[key] || spanishText;
    };
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", bootstrap);
  } else {
    bootstrap();
  }
})();
